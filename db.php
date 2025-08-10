<?php
// Kết nối MySQL
$host = 'localhost';
$user = 'root';
$password = ''; // hoặc mật khẩu của bạn
$dbname = 'hospital';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
