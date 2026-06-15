<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
</head>
<body>
    <h1>회원가입</h1>
    <form action="register_process.php" method="POST">
        <div>
            아이디: <input type="text" name="username" required>
        </div>
        <br>
        <div>
            비밀번호: <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">가입하기</button>
        <a href="login.php">로그인하러 가기</a>
    </form>
</body>
</html>
