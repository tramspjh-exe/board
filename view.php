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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.author_id = u.id WHERE p.id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='index.php';</script>";
    exit;
}

$c_stmt = $pdo->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.author_id = u.id WHERE c.post_id = :post_id ORDER BY c.id ASC");
$c_stmt->execute([':post_id' => $id]);
$comments = $c_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?></title>
    <script>
        function editComment(commentId, currentContent) {
            var newContent = prompt("댓글을 수정하세요:", currentContent);
            if (newContent !== null && newContent.trim() !== "") {
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "comment_edit.php";

                var idInput = document.createElement("input");
                idInput.type = "hidden";
                idInput.name = "id";
                idInput.value = commentId;
                form.appendChild(idInput);

                var contentInput = document.createElement("input");
                contentInput.type = "hidden";
                contentInput.name = "content";
                contentInput.value = newContent;
                form.appendChild(contentInput);

                var postIdInput = document.createElement("input");
                postIdInput.type = "hidden";
                postIdInput.name = "post_id";
                postIdInput.value = "<?= $id ?>";
                form.appendChild(postIdInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</head>
<body>
    <p><a href="index.php?board=<?= $post['board_type'] ?>">⬅️ 목록으로 돌아가기</a></p>
    
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <p>작성자: <?= htmlspecialchars($post['username']) ?> | 작성일: <?= $post['created_at'] ?></p>
    <hr>
    <div style="min-height: 200px; padding: 10px; border: 1px solid #ccc;">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>
    
    <?php if (!empty($post['stored_path'])): ?>
        <p>📎 첨부파일: <a href="download.php?file=<?= urlencode($post['stored_path']) ?>"><?= htmlspecialchars($post['original_name']) ?></a></p>
    <?php endif; ?>

    <?php if ($_SESSION['user_id'] == $post['author_id']): ?>
        <br>
        <a href="edit.php?id=<?= $post['id'] ?>"><button>글 수정</button></a>
        <a href="delete_process.php?id=<?= $post['id'] ?>" onclick="return confirm('정말 삭제하시겠습니까?');"><button>글 삭제</button></a>
    <?php endif; ?>

    <hr>
    <h3>💬 댓글 (<?= count($comments) ?>)</h3>
    
    <?php foreach ($comments as $comment): ?>
        <div style="border-bottom: 1px dashed #ccc; padding: 5px 0;">
            <strong><?= htmlspecialchars($comment['username']) ?></strong>: <?= htmlspecialchars($comment['content']) ?>
            
            <?php if ($_SESSION['user_id'] == $comment['author_id']): ?>
                <span style="font-size: 12px; margin-left: 10px;">
                    <a href="#" onclick="editComment(<?= $comment['id'] ?>, '<?= addslashes(htmlspecialchars($comment['content'])) ?>'); return false;">[수정]</a>
                    <a href="comment_delete.php?id=<?= $comment['id'] ?>&post_id=<?= $id ?>" onclick="return confirm('댓글을 삭제할까요?');">[삭제]</a>
                </span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <br>
    <form action="comment_process.php" method="POST">
        <input type="hidden" name="post_id" value="<?= $id ?>">
        <textarea name="content" rows="3" style="width: 100%;" required placeholder="댓글을 입력하세요."></textarea><br>
        <button type="submit">댓글 등록</button>
    </form>
</body>
</html>
