<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "BUKAN POST"]);
    exit;
}

/* Ambil data POST */
$nama = $_POST['nama'] ?? '';
$umur = $_POST['umur'] ?? 0;
$jk = $_POST['jk'] ?? '';
$aktivitas = $_POST['aktivitas'] ?? '';

/* Simpan ke database */
$conn->query("UPDATE data_diri SET start=0");
$sql2 = $conn->query(
    "INSERT INTO data_diri (nama, umur, jk, ja, start)
     VALUES ('$nama', '$umur', '$jk', '$aktivitas', 1)"
);

/* Siapkan response JSON */
if ($sql2) {
    $response = [
        "status" => "ok",
        "sesi_id" => $conn->insert_id  // bisa diganti sesuai kebutuhan
    ];
    echo json_encode($response);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
}
