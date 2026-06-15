<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT id, username, created_at FROM users";
if ($search !== '') {
    $sql .= " WHERE username LIKE :search";
}
$sql .= " ORDER BY username ASC";

$stmt = $pdo->prepare($sql);
$params = [];
if ($search !== '') {
    $params[':search'] = '%' . $search . '%';
}
$stmt->execute($params);
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>유저 검색</title>
</head>
<body>
    <p><a href="index.php">⬅️ 메인 게시판으로 돌아가기</a></p>
    
    <h1>🔍 유저 검색</h1>
    
    <form action="user_search.php" method="GET" style="margin-bottom: 20px;">
        유저 이름: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="검색할 아이디 입력">
        <button type="submit">검색</button>
        <?php if ($search !== ''): ?>
            <a href="user_search.php"><button type="button">초기화</button></a>
        <?php endif; ?>
    </form>

    <h3>가입된 회원 목록 (총 <?= count($users) ?>명)</h3>
    <table border="1" style="width: 60%; border-collapse: collapse;">
        <thead>
            <tr>
                <th width="20%">회원 번호</th>
                <th width="50%">아이디(이름)</th>
                <th width="30%">가입일</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="3" align="center">검색된 유저가 없습니다.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr <?= ($user['id'] == $_SESSION['user_id']) ? 'style="background-color: #f0f0f0; font-weight: bold;"' : '' ?>>
                        <td align="center"><?= $user['id'] ?></td>
                        <td style="padding-left: 10px;">
                            <?= htmlspecialchars($user['username']) ?>
                            <?= ($user['id'] == $_SESSION['user_id']) ? ' (나)' : '' ?>
                        </td>
                        <td align="center"><?= $user['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
