<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    if ($stmt->fetch()) {
        echo "<script>alert('이미 존재하는 아이디입니다.'); history.back();</script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':password' => $hashed_password
    ]);

    echo "<script>alert('회원가입이 완료되었습니다!'); location.href='login.php';</script>";
    exit;
} else {
    echo "잘못된 접근입니다.";
}
?>
