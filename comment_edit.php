<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $content = $_POST['content'];
    $post_id = (int)$_POST['post_id'];

    $sql = "UPDATE comments SET content = :content WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':content' => $content,
        ':id' => $id
    ]);

    header("Location: view.php?id=" . $post_id);
    exit;
} else {
    echo "잘못된 접근입니다.";
}
?>
