<?php
$host = "mysql"; 
$user = "root";
$pass = "root"; 
$db   = "login_test"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

