<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("SELECT original_name, stored_path FROM posts WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $current_file = $stmt->fetch();

    $original_name = $current_file['original_name'];
    $stored_path = $current_file['stored_path'];

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '/var/www/html/uploads/';
        
        if ($stored_path && file_exists($upload_dir . $stored_path)) {
            unlink($upload_dir . $stored_path);
        }

        $original_name = basename($_FILES['attachment']['name']);
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $stored_path = uniqid() . '-' . rand(100, 999) . '.' . $ext;
        move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_dir . $stored_path);
    }

    $sql = "UPDATE posts SET title = :title, content = :content, original_name = :original_name, stored_path = :stored_path WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':original_name' => $original_name,
        ':stored_path' => $stored_path,
        ':id' => $id
    ]);

    header("Location: view.php?id=" . $id);
    exit;
} else {
    echo "잘못된 접근입니다.";
}
?>
