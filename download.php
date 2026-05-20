<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$file = isset($_GET['file']) ? $_GET['file'] : '';
$file_path = '/var/www/html/uploads/' . basename($file);

if (file_exists($file_path) && is_file($file_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    
    readfile($file_path);
    exit;
} else {
    echo "파일이 존재하지 않습니다.";
}
?>
