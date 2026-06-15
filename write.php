<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$board_type = isset($_GET['board']) ? $_GET['board'] : 'free';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>글쓰기</title>
</head>
<body>
    <h1>새 글 작성 (<?= ($board_type === 'free') ? '자유게시판' : 'Q&A게시판' ?>)</h1>
    <form action="write_process.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="board_type" value="<?= htmlspecialchars($board_type) ?>">
        <div>
            제목: <input type="text" name="title" required style="width: 500px;">
        </div>
        <br>
        <div>
            내용:<br>
            <textarea name="content" rows="10" cols="70" required></textarea>
        </div>
        <br>
        <div>
            파일 첨부: <input type="file" name="attachment">
        </div>
        <br>
        <button type="submit">작성 완료</button>
        <a href="index.php?board=<?= $board_type ?>"><button type="button">취소</button></a>
    </form>
</body>
</html>
