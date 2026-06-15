<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT author_id, stored_path FROM posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if (!$post || $_SESSION['user_id'] != $post['author_id']) {
    echo "<script>alert('권한이 없습니다.'); location.href='index.php';</script>";
    exit;
}

$upload_dir = '/var/www/html/uploads/';
if ($post['stored_path'] && file_exists($upload_dir . $post['stored_path'])) {
    unlink($upload_dir . $post['stored_path']);
}

$stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: index.php");
exit;
?>
