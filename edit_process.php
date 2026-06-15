<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$id = (int)$_POST['id'];
$title = trim($_POST['title']);
$content = trim($_POST['content']);

$stmt = $pdo->prepare("SELECT author_id, stored_path FROM posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if (!$post || $_SESSION['user_id'] != $post['author_id']) {
    echo "<script>alert('권한이 없습니다.'); location.href='index.php';</script>";
    exit;
}

$stored_path = $post['stored_path'];
$original_name = null;

if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '/var/www/html/uploads/';
    if ($stored_path && file_exists($upload_dir . $stored_path)) {
        unlink($upload_dir . $stored_path);
    }
    $original_name = basename($_FILES['attachment']['name']);
    $ext = pathinfo($original_name, PATHINFO_EXTENSION);
    $stored_path = uniqid() . '-' . rand(100, 999) . '.' . $ext;
    move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_dir . $stored_path);

    $sql = "UPDATE posts SET title = :title, content = :content, original_name = :original_name, stored_path = :stored_path WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':original_name' => $original_name, ':stored_path' => $stored_path, ':id' => $id]);
} else {
    $sql = "UPDATE posts SET title = :title, content = :content WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':title' => $title, ':content' => $content, ':id' => $id]);
}

header("Location: view.php?id=" . $id);
exit;
?>
