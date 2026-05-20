<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = 1;
    $original_name = null;
    $stored_path = null;

    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '/var/www/html/uploads/';
        $original_name = basename($_FILES['attachment']['name']);
        
        $ext = pathinfo($original_name, PATHINFO_EXTENSION);
        $unique_name = uniqid() . '-' . rand(100, 999) . '.' . $ext;
        
        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $upload_dir . $unique_name)) {
            $stored_path = $unique_name;
        }
    }

    $sql = "INSERT INTO posts (title, content, author_id, original_name, stored_path) VALUES (:title, :content, :author_id, :original_name, :stored_path)";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':title' => $title,
        ':content' => $content,
        ':author_id' => $author_id,
        ':original_name' => $original_name,
        ':stored_path' => $stored_path
    ]);

    header("Location: index.php");
    exit;
} else {
    echo "잘못된 접근입니다.";
}
?>
