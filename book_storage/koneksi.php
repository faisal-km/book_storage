<?php
$host = 'localhost';
$user = 'root';
$password = ''; // Ganti sesuai password MySQL Anda
$dbname = 'book_storage';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>