<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<script>alert('존재하지 않는 글입니다.'); location.href='index.php';</script>";
    exit;
}

// ⚠️ 보안: 타인의 글 수정 시도 차단
if ($_SESSION['user_id'] != $post['author_id']) {
    echo "<script>alert('본인의 글만 수정할 수 있습니다.'); location.href='index.php';</script>";
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
    <h1>글 수정</h1>
    <form action="edit_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $post['id'] ?>">
        <div>
            제목: <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required style="width: 500px;">
        </div>
        <br>
        <div>
            내용:<br>
            <textarea name="content" rows="10" cols="70" required><?= htmlspecialchars($post['content']) ?></textarea>
        </div>
        <br>
        <div>
            현재 파일: <?= $post['original_name'] ? htmlspecialchars($post['original_name']) : '없음' ?><br>
            변경할 파일: <input type="file" name="attachment">
        </div>
        <br>
        <button type="submit">수정 완료</button>
        <a href="view.php?id=<?= $post['id'] ?>"><button type="button">취소</button></a>
    </form>
</body>
</html>
