<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $board_type = $_POST['board_type'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author_id = $_SESSION['user_id']; 

    $original_name = null;
    $stored_path = null;

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '/var/www/html/uploads/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $original_name = basename($_FILES['attachment']['name']);
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $stored_path = uniqid() . '-' . rand(100, 999) . '.' . $ext;

        move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_dir . $stored_path);
    }

    $sql = "INSERT INTO posts (board_type, title, content, author_id, original_name, stored_path) 
            VALUES (:board_type, :title, :content, :author_id, :original_name, :stored_path)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':board_type' => $board_type,
        ':title' => $title,
        ':content' => $content,
        ':author_id' => $author_id,
        ':original_name' => $original_name,
        ':stored_path' => $stored_path
    ]);

    header("Location: index.php?board=" . $board_type);
    exit;
}
?>
} else {
    echo "잘못된 접근입니다.";
}
?>
