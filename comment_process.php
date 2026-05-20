<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $post_id = (int)$_POST['post_id'];
    $content = $_POST['content'];
    $author_id = 1;

    $sql = "INSERT INTO comments (post_id, author_id, content) VALUES (:post_id, :author_id, :content)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':post_id' => $post_id,
        ':author_id' => $author_id,
        ':content' => $content
    ]);

    header("Location: view.php?id=" . $post_id);
    exit;
} else {
    echo "잘못된 접근입니다.";
}
?>
