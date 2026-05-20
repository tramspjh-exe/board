<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require 'db.php'; 


$stmt = $pdo->query("SELECT p.*, u.username FROM posts p JOIN users u ON p.author_id = u.id ORDER BY p.id DESC");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>나의 PHP 게시판</title>
</head>
<body>
    <h1>게시글 목록</h1>
    <a href="write.php"><button>글쓰기</button></a>
    
    <table border="1" style="width: 100%; margin-top: 10px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>번호</th>
                <th>제목</th>
                <th>작성자</th>
                <th>작성일</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
                <tr><td colspan="4" style="text-align:center;">게시글이 없습니다.</td></tr>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?= $post['id'] ?></td>
                        <td><a href="view.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></td>
                        <td><?= htmlspecialchars($post['username']) ?></td>
                        <td><?= $post['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
