<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "kuliah";

$conn = mysqli_connect("localhost", "root", "", "db_mhs");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
