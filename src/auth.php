<?php
include "db.php";

function loginFunction($email, $password)
{
    global $conn;
    $email = strtolower(trim($email));

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if (!$user) {
        return "gagal";
    }

    if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
        return "locked";
    }

    if (password_verify($password, $user['password'])) {
        // reset gagal login
        $conn->query("UPDATE users SET failed_login=0, locked_until=NULL WHERE email='$email'");
        return "berhasil";
    }

    // gagal login
    $failed = $user['failed_login'] + 1;
    if ($failed >= 3) {
        $lock_time = date("Y-m-d H:i:s", strtotime("+5 minutes"));
        $conn->query("UPDATE users SET failed_login=0, locked_until='$lock_time' WHERE email='$email'");
        return "locked";
    } else {
        $conn->query("UPDATE users SET failed_login=$failed WHERE email='$email'");
        return "gagal";
    }
}

function registerFunction($nama, $email, $password)
{
    global $conn;
    $email = strtolower(trim($email));

    if (empty($nama) || empty($email) || empty($password)) {
        return "error";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "error";
    }

    if (strlen($password) < 8) {
        return "error";
    }

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        return "terdaftar";
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $conn->query("INSERT INTO users (nama, email, password) VALUES ('$nama','$email','$hash')");

    return "berhasil";
}
