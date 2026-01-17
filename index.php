<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Timbangan Digital</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f2f2f2;
}
.container {
    max-width: 500px;
    margin: 40px auto;
    background: #fff;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
}
.hasil {
    font-size: 2rem;
}
.label {
    text-align: center;
    font-weight: bold;
    margin-top: -5px;
}
</style>

</head>
<body>
<div class="container">

<h2 class="text-center mb-4">INPUT DATA DIRI</h2>

<div class="mb-3">
    <label for="nama" class="form-label">Nama</label>
    <input type="text" id="nama" class="form-control">
</div>

<div class="mb-3">
    <label for="umur" class="form-label">Umur</label>
    <input type="number" id="umur" class="form-control">
</div>

<div class="mb-3">
    <label for="jk" class="form-label">Jenis Kelamin</label>
    <select id="jk" class="form-select">
        <option value="L">Pria</option>
        <option value="P">Wanita</option>
    </select>
</div>

<div class="mb-3">
    <label for="aktivitas" class="form-label">Jenis Aktivitas</label>
    <select id="aktivitas" class="form-select">
        <option value="ringan">Ringan</option>
        <option value="sedang">Sedang</option>
        <option value="rutin">Rutin</option>
        <option value="berat">Berat</option>
    </select>
</div>

<button onclick="start()" class="btn btn-success w-100 mb-4">START</button>

<h2 class="text-center mb-3">HASIL PENGUKURAN</h2>
<div class="d-flex justify-content-around bg-light p-3 rounded hasil mb-2">
    <div><span id="tinggi">0</span> cm</div>
    <div><span id="berat">0</span> kg</div>
</div>
<div class="label">TINGGI BADAN || BERAT BADAN</div>

<h2 class="text-center mt-4 mb-3">HASIL PERHITUNGAN</h2>
<div class="bg-light p-3 rounded">
    Status Badan: <span id="status">-</span><br>
    Berat Badan Ideal: <span id="bbi">0</span> Kg<br>
    Rekomendasi Kalori Harian: <span id="kalori">0</span> Kkal<br>
    Tujuan: <span id="tujuan">-</span><br>
    Durasi Pemulihan: <span id="durasi">0</span> Minggu
</div>

</div>

<!-- Bootstrap JS (optional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let sesi_id = null;

async function start() {
    const data = new FormData();
    data.append("nama", nama.value);
    data.append("umur", umur.value);
    data.append("jk", jk.value);
    data.append("aktivitas", aktivitas.value);

    try {
        const res = await fetch("start.php", {
            method: "POST",
            body: data
        });

        // debug: baca sebagai text dulu
        const text = await res.text();
        console.log("RAW RESPONSE:", text);

        // convert ke JSON
        const json = JSON.parse(text);

        if(json.sesi_id){
            sesi_id = json.sesi_id;
            alert("Silakan naik ke timbangan");
            listenRealtime();
        }
    } catch (err) {
        console.error("ERROR START:", err);
        alert("Gagal memulai sesi");
    }
}

// ==== WebSocket Realtime ====
let ws = null;

function listenRealtime(){
    if(!sesi_id) return;

    ws = new WebSocket("ws://192.168.101.222:5000"); // ganti sesuai server websocket

    ws.onopen = () => {
        console.log("WebSocket connected");
        // Kirim subscribe sesi_id
        ws.send(JSON.stringify({action:"subscribe", sesi_id: sesi_id}));
    };

    ws.onmessage = (event) => {
        const data = JSON.parse(event.data);
        // Pastikan sesi_id sesuai
        if(data.sesi_id !== sesi_id) return;

        // Update tampilan realtime
        if(data.tinggi !== undefined) document.getElementById("tinggi").innerText = data.tinggi;
        if(data.berat  !== undefined) document.getElementById("berat").innerText = data.berat;
        if(data.status !== undefined) document.getElementById("status").innerText = data.status;
        if(data.bbi    !== undefined) document.getElementById("bbi").innerText = data.bbi;
        if(data.kalori !== undefined) document.getElementById("kalori").innerText = data.kalori;
        if(data.tujuan !== undefined) document.getElementById("tujuan").innerText = data.tujuan;
        if(data.durasi !== undefined) document.getElementById("durasi").innerText = data.durasi;
    };

    ws.onclose = () => {
        console.log("WebSocket disconnected, reconnect in 2s");
        setTimeout(listenRealtime,2000);
    };

    ws.onerror = (err) => {
        console.error("WebSocket error", err);
        ws.close();
    };
}
</script>

</body>
</html>
