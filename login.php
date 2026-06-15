<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인</title>
</head>
<body>
    <h1>로그인</h1>
    <form action="login_process.php" method="POST">
        <div>
            아이디: <input type="text" name="username" required>
        </div>
        <br>
        <div>
            비밀번호: <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">로그인</button>
        <a href="register.php">회원가입</a>
    </form>
</body>
</html>
