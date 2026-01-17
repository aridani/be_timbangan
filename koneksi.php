<?php
$conn = new mysqli("localhost", "root", "", "timblok");
if ($conn->connect_error) {
    die("Koneksi gagal");
}
?>
