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

$stmt = $pdo->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.author_id = u.id WHERE p.id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    echo "존재하지 않는 게시글입니다.";
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
</head>
<body>
    <h1><?= htmlspecialchars($post['title']) ?></h1>
    <p><strong>작성자:</strong> <?= htmlspecialchars($post['username']) ?> | <strong>작성일:</strong> <?= $post['created_at'] ?></p>
    <hr>
    <div style="min-height: 200px; padding: 10px; background: #f9f9f9; border: 1px solid #ddd; margin-bottom: 20px;">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>

    <?php if (!empty($post['stored_path'])): ?>
        <p><strong>첨부파일:</strong> <a href="download.php?file=<?= urlencode($post['stored_path']) ?>"><?= htmlspecialchars($post['original_name']) ?></a></p>
    <?php endif; ?>
    
    <a href="index.php"><button>목록으로</button></a>
    <a href="edit.php?id=<?= $id ?>"><button>수정</button></a>
    <a href="delete_process.php?id=<?= $id ?>" onclick="return confirm('정말 삭제하시겠습니까?');"><button>삭제</button></a>
    <hr>

    <h3>댓글 목록</h3>
    <div style="margin-bottom: 20px;">
        <?php if (empty($comments)): ?>
            <p>작성된 댓글이 없습니다.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div style="border-bottom: 1px dashed #ccc; padding: 5px 0;">
                    <strong><?= htmlspecialchars($comment['username']) ?></strong> (<?= $comment['created_at'] ?>)
                    <span style="font-size: 12px; margin-left: 10px;">
                        <a href="#" onclick="editComment(<?= $comment['id'] ?>, '<?= urlencode($comment['content']) ?>', <?= $id ?>); return false;">수정</a> | 
                        <a href="comment_delete.php?id=<?= $comment['id'] ?>&post_id=<?= $id ?>" onclick="return confirm('댓글을 삭제하시겠습니까?');">삭제</a>
                    </span>
                    <br>
                    <?= nl2br(htmlspecialchars($comment['content'])) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form action="comment_process.php" method="POST">
        <input type="hidden" name="post_id" value="<?= $id ?>">
        <textarea name="content" rows="3" style="width: 100%;" required placeholder="댓글을 입력하세요."></textarea><br>
        <button type="submit" style="margin-top: 5px;">댓글 등록</button>
    </form>

    <script>
    function editComment(commentId, currentContent, postId) {
        var newContent = prompt("댓글을 수정하세요:", decodeURIComponent(currentContent.replace(/\+/g, ' ')));
        if (newContent !== null && newContent.trim() !== "") {
            var form = document.createElement("form");
            form.method = "POST";
            form.action = "comment_edit.php";
            
            var i1 = document.createElement("input");
            i1.type = "hidden"; i1.name = "id"; i1.value = commentId;
            form.appendChild(i1);
            
            var i2 = document.createElement("input");
            i2.type = "hidden"; i2.name = "content"; i2.value = newContent;
            form.appendChild(i2);
            
            var i3 = document.createElement("input");
            i3.type = "hidden"; i3.name = "post_id"; i3.value = postId;
            form.appendChild(i3);
            
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</body>
</html>
