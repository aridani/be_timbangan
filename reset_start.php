<?php
include "koneksi.php";

$conn->query("UPDATE data_diri SET start=0");
echo "RESET";
