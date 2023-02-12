<?php
    $title = 'ホーム';
    include './common/head.php'; // head.php の読み込み
?>
<?php
    session_start();
    if (empty($_SESSION['USERNAME'])){
        header('Location: login.php');
        exit;
    }
    $username = $_SESSION['USERNAME'];
    $user_id = $_SESSION['USERID'];
    
    require_once('./config/config.php');
    
    // 編集フォーム初期値
    $thing_id_edit_form = 0;
    $name_edit_form = '';
    $amount_edit_form = '';
    $unit_edit_form = '';
    $detail_edit_form = '';
    $deadline_edit_form = '';
?>
<?php
    if(!empty($_POST['change-thing-button'])) {
        $pdo = new PDO(DSN, DB_USER, DB_PASS);
        // ID と パスワード が一致するコメントを選択
        $sql = 'SELECT id, name, amount, unit, detail, deadline FROM things WHERE id=:id AND user_id=:user_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $_POST['edit_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        // 一件取得
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // フォームに表示
        $thing_id_edit_form = $result['id'];
        $name_edit_form = $result['name'];
        $amount_edit_form = $result['amount'];
        $unit_edit_form = $result['unit'];
        $detail_edit_form = $result['detail'];
        $deadline_edit_form = $result['deadline'];
    }
?>
<body>
    <?php include './common/header.php'; ?>
    
    <div class="container">
        <div class="row text-center my-5">
            <h2><?= $username ?>さんのお買い物リスト</h2>
        </div>
        <div class="row mb-3">
            <div class="col">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addThingsModal">追加</button>
            </div>
        </div>
        <div class="row mb-3">
            <?php
                $pdo = new PDO(DSN, DB_USER, DB_PASS);
                $sql = 'SELECT id, name, amount, unit, detail, deadline, is_done FROM things WHERE user_id = :user_id ORDER BY deadline ASC';
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt -> execute();
                $results = $stmt -> fetchAll();
                
                $prev_deadline = 'xxxx/xx/xx';
                $is_beginning = true;
                foreach ($results as $row) {
                    if ($row['deadline'] != $prev_deadline) {
                        $prev_deadline = $row['deadline'];
                        $view_deadline = $row['deadline'] == NULL ? '期限なし' : $row['deadline'];
            ?>
            <?php
                        if (!$is_beginning) {
            ?>
            </ul>
            <?php
                        } else {
                            $is_beginning = false;
                        }
            ?>
            <ul class="list-group col-12 col-sm-6 col-md-4">
                <h5 class="mb-1"><?= $view_deadline ?></h5>

            <?php
                    }
            ?>
                
                <li class="list-group-item"
                <?php  if ((int)$row['is_done'] == 1) : ?>
                    style="text-decoration: line-through;"
                <?php endif; ?>
                
                >
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1"><?= $row['name'] ?></h6>
                        <small>
                            <span class="text-end d-inline-block" style="width:3rem;"><?= $row['amount'] ?></span> 
                            <span class="d-inline-block" style="width:3rem;"><?= $row['unit'] ?></span>
                        </small>
                    </div>
                    <small class="text-muted" style="white-space:pre-wrap;"><?= $row['detail'] ?></small>
                    <div class="text-end">
                        <form class="d-inline-block" action="./things/done.php" method="POST">
                            <input name="thing_id" type="hidden" value="<?= $row['id'] ?>">
                            <?php if ((int)$row['is_done'] != 1) : ?>
                                <button type="submit" name="done_button" value="to_done" class="btn btn-outline-primary btn-sm">完了にする</button>
                            <?php else : ?>
                                <button type="submit" name="done_button" value="to_doing" class="btn btn-primary btn-sm">未完了にする</button>
                            <?php endif; ?>
                        </form>
                        <form class="d-inline-block" action="" method="POST">
                            <input name="edit_id" type="hidden" value="<?= $row['id'] ?>">
                            <button name="change-thing-button" type="submit" value="edit" class="btn btn-outline-success btn-sm">編集</button>
                        </form>
                        <form class="d-inline-block" action="./things/delete.php" method="POST">
                            <input name="delete_id" type="hidden" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-outline-danger btn-sm">削除</button>
                        </form>
                    </div>
                </li>
            
            <?php
                }
            ?>
            </ul>
        </div>
    </div>
    
    <!-- 追加Modal -->
    <div class="modal fade" id="addThingsModal" tabindex="-1" aria-labelledby="addThingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="./things/add.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addThingsModalLabel">追加</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">商品名</label>
                        <input type="text" class="form-control" name="name" placeholder="欲しい物を入力してね！" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">個数</label>
                        <div class="row g-3 align-items-center">
                            <div class="col-3">
                                <input type="number" class="form-control" name="amount" placeholder="1" required>
                            </div>
                            <div class="col-3">
                                <input type="text" class="form-control" name="unit" placeholder="個">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">詳細</label>
                        <textarea class="form-control" name="detail" placeholder="詳細があったら入力してね！"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">期限</label>
                        <input type="date" class="form-control" name="deadline">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    <button type="submmit" class="btn btn-primary">追加</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- 編集Modal -->
    <div class="modal fade" id="editThingsModal" tabindex="-1" aria-labelledby="editThingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" action="./things/edit.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editThingsModalLabel">編集</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="thing_id" value="<?= $thing_id_edit_form ?>" required>
                    <div class="mb-3">
                        <label class="form-label">商品名</label>
                        <input type="text" class="form-control" name="name" value="<?= $name_edit_form ?>" placeholder="欲しい物を入力してね！" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">個数</label>
                        <div class="row g-3 align-items-center">
                            <div class="col-3">
                                <input type="number" class="form-control" name="amount" value="<?= $amount_edit_form ?>" placeholder="1" required>
                            </div>
                            <div class="col-3">
                                <input type="text" class="form-control" name="unit" value="<?= $unit_edit_form ?>" placeholder="個">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">詳細</label>
                        <textarea class="form-control" name="detail" placeholder="詳細があったら入力してね！"><?= $detail_edit_form ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">期限</label>
                        <input type="date" class="form-control" name="deadline" value="<?= $deadline_edit_form ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                    <button type="submmit" class="btn btn-primary">編集</button>
                </div>
            </form>
        </div>
    </div>
    <?php if(!empty($_POST['change-thing-button'])): ?>
        <script>
            const editModal = new bootstrap.Modal(document.getElementById('editThingsModal'));
            editModal.show();
        </script>
    <?php endif; ?>
</body>
</html>