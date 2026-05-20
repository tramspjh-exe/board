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

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    echo "존재하지 않는 게시글입니다.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>글 수정</title>
</head>
<body>
    <h1>게시글 수정</h1>
    <form action="edit_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <div>
            제목: <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required style="width: 50%;">
        </div>
        <br>
        <div>
            본문:<br>
            <textarea name="content" rows="10" cols="50" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <br>
        <div>
            현재 파일: <?= $post['original_name'] ? htmlspecialchars($post['original_name']) : '없음' ?><br>
            변경할 파일 첨부: <input type="file" name="attachment">
        </div>
        <br>
        <button type="submit">수정 완료</button>
        <a href="view.php?id=<?= $post['id'] ?>">취소</a>
    </form>
</body>
</html>
