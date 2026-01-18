<?php
$conn = new mysqli("localhost", "root", "dblocal", "timblok");
if ($conn->connect_error) {
    die("Koneksi gagal");
}
