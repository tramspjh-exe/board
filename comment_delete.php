<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

if ($id <= 0 || $post_id <= 0) {
    echo "잘못된 접근입니다.";
    exit;
}

$stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: view.php?id=" . $post_id);
exit;
?>
