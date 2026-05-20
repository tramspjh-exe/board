<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    echo "잘못된 접근입니다.";
    exit;
}

$stmt = $pdo->prepare("SELECT stored_path FROM posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if ($post) {
    if (!empty($post['stored_path'])) {
        $file_path = '/var/www/html/uploads/' . $post['stored_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    $del_comments = $pdo->prepare("DELETE FROM comments WHERE post_id = :post_id");
    $del_comments->execute([':post_id' => $id]);

    $del_post = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $del_post->execute([':id' => $id]);
}

header("Location: index.php");
exit;
?>
