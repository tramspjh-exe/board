<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$id = (int)$_POST['id'];
$content = trim($_POST['content']);
$post_id = (int)$_POST['post_id'];

$stmt = $pdo->prepare("SELECT author_id FROM comments WHERE id = :id");
$stmt->execute([':id' => $id]);
$comment = $stmt->fetch();

if (!$comment || $_SESSION['user_id'] != $comment['author_id']) {
    echo "<script>alert('댓글 수정 권한이 없습니다.'); location.href='view.php?id=' + $post_id;";
    exit;
}

$stmt = $pdo->prepare("UPDATE comments SET content = :content WHERE id = :id");
$stmt->execute([':content' => $content, ':id' => $id]);

header("Location: view.php?id=" . $post_id);
exit;
?>
