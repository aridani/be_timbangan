
#include <HX711.h>
#include <WiFi.h>
#include <esp_now.h>
#include <ESP32Firebase.h>

/* ===== WIFI & FIREBASE ===== */
#define WIFI_SSID "UNPAMSERANG"
#define WIFI_PASS "123123123"

#define REFERENCE_URL "https://berattinggi-default-rtdb.firebaseio.com/"
#define FIREBASE_AUTH "ViD3kXwTZm2P0PBCQlOILzkW2WgGpueWlmcKd65y"

/* ===== PIN ===== */
#define DT 4
#define SCK 5
#define BUZZER 23

HX711 scale;
Firebase firebase(REFERENCE_URL);

/* ===== DATA ESP-NOW ===== */
typedef struct
{
    float jarak; // tinggi dari ESP32 pengirim
} DataKirim;

DataKirim dataTerima;
float tinggiESPNow = 0;

float calibration_factor = -7050;

/* ===== BUZZER ===== */
void beep(int jumlah)
{
    for (int i = 0; i < jumlah; i++)
    {
        digitalWrite(BUZZER, HIGH);
        delay(200);
        digitalWrite(BUZZER, LOW);
        delay(200);
    }
}

/* ===== CALLBACK ESP-NOW ===== */
void onDataRecv(const uint8_t *mac, const uint8_t *incomingData, int len)
{
    memcpy(&dataTerima, incomingData, sizeof(dataTerima));
    tinggiESPNow = dataTerima.jarak;

    // Serial.print("Tinggi diterima (ESP-NOW): ");
    // Serial.println(tinggiESPNow);
}

bool prosesAktif = false;
unsigned long waktuMulai = 0;
const unsigned long durasiProses = 5000;

/* ===== SETUP ===== */
void setup()
{
    Serial.begin(9600);

    pinMode(BUZZER, OUTPUT);

    scale.begin(DT, SCK);
    scale.set_scale(calibration_factor);
    scale.tare();

    WiFi.mode(WIFI_STA); // PENTING
    WiFi.begin(WIFI_SSID, WIFI_PASS);

    WiFi.disconnect(); // WAJIB

    if (esp_now_init() != ESP_OK)
    {
        Serial.println("ESP-NOW GAGAL");
        return;
    }

    esp_now_register_recv_cb(onDataRecv);

    Serial.println("ESP32 PENERIMA READY");

    uint8_t senderMAC[] = {0xA0, 0xB7, 0x65, 0x25, 0xAC, 0x28};

    esp_now_peer_info_t peerInfo = {};
    memcpy(peerInfo.peer_addr, senderMAC, 6);
    peerInfo.channel = WiFi.channel();
    peerInfo.encrypt = false;

    if (esp_now_add_peer(&peerInfo) != ESP_OK)
    {
        Serial.println("Gagal tambah peer");
    }
}

/* ===== LOOP ===== */
void loop()
{

    float berat1 = scale.get_units(10);
    float berat = berat1 - 63;
    float tinggi = 200 - tinggiESPNow;

    Serial.print("Berat: ");
    Serial.println(berat);

    Serial.print("Tinggi (ESP-NOW): ");
    Serial.println(tinggi);

    // ===== BACA PERINTAH START =====
    int start = firebase.getString("/mulai/start").toInt();

    if (start == 1 && !prosesAktif)
    {
        prosesAktif = true;
        waktuMulai = millis();
        beep(1);
        Serial.println("PROSES DIMULAI");
    }

    if (prosesAktif && millis() - waktuMulai >= durasiProses)
    {

        float berat1 = scale.get_units(10);
        float berat = berat1 - 63;
        float tinggi = 200 - tinggiESPNow;

        Serial.print("Berat: ");
        Serial.println(berat);

        Serial.print("Tinggi (ESP-NOW): ");
        Serial.println(tinggi);

        String nama = firebase.getString("input/Nama");
        int umur = firebase.getInt("input/Umur");
        String jk = firebase.getString("input/Jenis_Kelamin");
        String aktivitas = firebase.getString("input/Aktivitas");

        float bmi = berat / pow((tinggi / 100), 2);

        String status;
        if (bmi < 18.5)
            status = "Kurus";
        else if (bmi < 25)
            status = "Ideal";
        else if (bmi < 30)
            status = "Overweight";
        else
            status = "Obesitas";

        float bbi = 0, bmr = 0, tdee = 0, rekomendasiKalori = 0;
        int PBB = 0, durasi = 0;
        String tujuan = "-";

        if (status != "Ideal")
        {

            bbi = (jk == "Pria") ? (tinggi - 100) * 0.9 : (tinggi - 100) * 0.85;
            bmr = (jk == "Pria") ? (10 * berat) + (6.25 * tinggi) - (5 * umur) : (10 * berat) + (3.1 * tinggi) - (4.3 * umur) - 161;

            PBB = abs(berat - bbi);

            float faktor = 1.2;
            if (aktivitas == "Ringan")
                faktor = 1.375;
            else if (aktivitas == "Sedang")
                faktor = 1.55;
            else if (aktivitas == "Rutin")
                faktor = 1.725;
            else if (aktivitas == "Berat")
                faktor = 1.9;

            tdee = bmr * faktor;

            if (status == "Kurus")
            {
                rekomendasiKalori = tdee + 500;
                tujuan = "Surplus";
            }
            else
            {
                rekomendasiKalori = tdee - 500;
                tujuan = "Defisit";
            }

            durasi = PBB / 0.5;
        }

        /*firebase.setFloat("sensor/berat", berat);
        firebase.setFloat("sensor/tinggi", tinggi);
        firebase.setFloat("hasil/bmi", bmi);
        firebase.setString("hasil/status", status);
        firebase.setFloat("hasil/bbi", bbi);
        firebase.setFloat("hasil/bmr", bmr);
        firebase.setFloat("hasil/tdee", tdee);
        firebase.setFloat("hasil/durasi", durasi);
        firebase.setFloat("hasil/rekomendasi_kalori", rekomendasiKalori);
        firebase.setString("hasil/tujuan", tujuan);*/

        beep(2);
        firebase.setInt("/mulai/start", 0);
        prosesAktif = false;

        Serial.println("PROSES SELESAI");
    }

    delay(500);
}
