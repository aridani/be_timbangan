<?php
include "koneksi.php";

$data = $conn->query("SELECT * FROM hasil_pengukuran ORDER BY id DESC LIMIT 1")
              ->fetch_assoc();

echo json_encode([
    "tinggi" => $data['tinggi'],
    "berat"  => $data['berat'],
    "status" => $data['status'],
    "bbi"    => $data['bbi'],
    "kalori" => $data['kalori'],
    "tujuan" => $data['tujuan'],
    "durasi" => $data['durasi']
]);
