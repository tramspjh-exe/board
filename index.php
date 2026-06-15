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

$board_type = isset($_GET['board']) ? $_GET['board'] : 'free';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'desc';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$order_by = ($sort === 'asc') ? 'ASC' : 'DESC';

$sql = "SELECT p.*, u.username FROM posts p 
        JOIN users u ON p.author_id = u.id 
        WHERE p.board_type = :board_type";

if ($search !== '') {
    $sql .= " AND (p.title LIKE :search OR p.content LIKE :search)";
}

$sql .= " ORDER BY p.id $order_by";

$stmt = $pdo->prepare($sql);
$params = [':board_type' => $board_type];
if ($search !== '') {
    $params[':search'] = '%' . $search . '%';
}
$stmt->execute($params);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>메인 게시판</title>
</head>
<body>
    <div style="text-align: right;">
        <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>님 환영합니다! 
        <a href="logout.php"><button>로그아웃</button></a>
    </div>

    <h1>📋 <?= ($board_type === 'free') ? '자유 게시판' : 'Q&A 게시판' ?></h1>

    <div style="margin-bottom: 20px;">
        <a href="index.php?board=free"><button <?= ($board_type === 'free') ? 'style="font-weight:bold;"' : '' ?>>자유게시판 이동</button></a>
        <a href="index.php?board=qa"><button <?= ($board_type === 'qa') ? 'style="font-weight:bold;"' : '' ?>>Q&A게시판 이동</button></a>
    </div>

    <form action="index.php" method="GET" style="margin-bottom: 10px;">
        <input type="hidden" name="board" value="<?= htmlspecialchars($board_type) ?>">
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        검색: <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="제목 또는 내용 검색">
        <button type="submit">검색</button>
        <?php if ($search !== ''): ?>
            <a href="index.php?board=<?= $board_type ?>&sort=<?= $sort ?>"><button type="button">초기화</button></a>
        <?php endif; ?>
    </form>

    <div style="margin-bottom: 10px;">
        정렬: 
        <a href="index.php?board=<?= $board_type ?>&sort=desc&search=<?= urlencode($search) ?>" <?= ($sort === 'desc') ? 'style="font-weight:bold;"' : '' ?>>최신순</a> | 
        <a href="index.php?board=<?= $board_type ?>&sort=asc&search=<?= urlencode($search) ?>" <?= ($sort === 'asc') ? 'style="font-weight:bold;"' : '' ?>>오래된순</a>
    </div>

    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th width="10%">번호</th>
                <th width="50%">제목</th>
                <th width="20%">작성자</th>
                <th width="20%">작성일</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
                <tr>
                    <td colspan="4" align="center">게시글이 존재하지 않습니다.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td align="center"><?= $post['id'] ?></td>
                        <td>
                            <a href="view.php?id=<?= $post['id'] ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </td>
                        <td align="center"><?= htmlspecialchars($post['username']) ?></td>
                        <td align="center"><?= $post['created_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <a href="write.php?board=<?= $board_type ?>"><button>글쓰기</button></a>
</body>
</html>
