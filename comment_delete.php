<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

$stmt = $pdo->prepare("SELECT author_id FROM comments WHERE id = :id");
$stmt->execute([':id' => $id]);
$comment = $stmt->fetch();

if (!$comment || $_SESSION['user_id'] != $comment['author_id']) {
    echo "<script>alert('댓글 삭제 권한이 없습니다.'); location.href='view.php?id=' + $post_id;";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: view.php?id=" . $post_id);
exit;
?>
