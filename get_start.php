<?php
include "koneksi.php";

$data = $conn->query("SELECT * FROM data_diri WHERE start=1 ORDER BY id DESC LIMIT 1")
              ->fetch_assoc();

echo json_encode([
    "nama" => $data['nama'],
    "umur"  => $data['umur'],
    "id" => $data['id'],
    "jk"=> $data["jk"],
    "ja"=>$data["ja"],
    "start"=>$data['start']
]);
