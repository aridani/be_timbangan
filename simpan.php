<?php
$conn = new mysqli("localhost", "root", "", "timblok");

if ($conn->connect_error) {
    die("Koneksi gagal");
}
$sesi_id= $_POST["sesi_id"];
$nama       = $_POST['nama'];
$umur       = $_POST['umur'];
$jk         = $_POST['jk'];
$aktivitas  = $_POST['aktivitas'];
$berat      = $_POST['berat'];
$tinggi     = $_POST['tinggi'];
$bmi        = $_POST['bmi'];
$status     = $_POST['status'];
$bbi        = $_POST['bbi'];
$bmr        = $_POST['bmr'];
$tdee       = $_POST['tdee'];
$kalori     = $_POST['kalori'];
$durasi     = $_POST['durasi'];
$tujuan     = $_POST['tujuan'];

$sql = "INSERT INTO hasil_pengukuran 
(sesi_id,nama, umur, jk, aktivitas, berat, tinggi, bmi, status, bbi, bmr, tdee, kalori, durasi, tujuan)
VALUES
('$sesi_id','$nama','$umur','$jk','$aktivitas','$berat','$tinggi','$bmi','$status','$bbi','$bmr','$tdee','$kalori','$durasi','$tujuan')";

if ($conn->query($sql)) {
    echo "OK";
} else {
    echo "ERROR";
}

$conn->close();
?>
