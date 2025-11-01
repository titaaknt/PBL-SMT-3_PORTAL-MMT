<?php
// Konfigurasi koneksi PostgreSQL
$host = "localhost";
$port = "5432";
$dbname = "portal_mmt";
$user = "postgres";
$password = "12345678";

// Membuat koneksi
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

// Cek koneksi
if (!$conn) {
    die("Koneksi database gagal: " . pg_last_error());
}
?>