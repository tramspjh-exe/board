<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>글쓰기</title>
</head>
<body>
    <h1>새 글 작성</h1>
    <form action="write_process.php" method="POST" enctype="multipart/form-data">
        <p>제목: <input type="text" name="title" required></p>
        <p>본문: <textarea name="content" rows="10" cols="50" required></textarea></p>
        <p>파일 첨부: <input type="file" name="attachment"></p>
        <button type="submit">작성 완료</button>
        <a href="index.php">취소</a>
    </form>
</body>
</html>
