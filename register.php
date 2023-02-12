<?php
    $title = '新規登録';
    include './common/head.php'; // head.php の読み込み
?>

<?php
    session_start();
    if (!empty($_SESSION['USERNAME'])) {
        header('Location: home.php');
        exit;
    }
    
    $error_message = '';
    
    if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['valid_password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $valid_password = $_POST['valid_password'];
    
        require_once('./config/config.php');
        //データベースへ接続、テーブルがない場合は作成
        try {
            $pdo = new PDO(DSN, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("create table if not exists users(
                        id int not null auto_increment primary key,
                        username varchar(255) not null,
                        password varchar(255) not null,
                        created_at timestamp not null default current_timestamp
                        )");
        } catch (Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
        
        // ユーザ名の入力値バリデーション（正規表現）
        if (!preg_match('/\A[\w]{1,100}+\z/', $username)) {
            $error_message = 'ユーザ名は半角英数字で1文字以上で設定してください。';
        }
        if ($password != $valid_password) {
            $error_message = 'パスワードが確認用パスワードと一致しません。';
        }
        // パスワードの入力値バリデーション（正規表現）
        if (preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/', $password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $error_message = 'パスワードは半角英数字をそれぞれ1文字以上含んだ8文字以上で設定してください。';
        }
        
        // ユーザ名の既存バリデーション
        $stmt = $pdo->prepare("SELECT username FROM users WHERE username = :username");
        $stmt -> bindParam(':username', $username, PDO::PARAM_STR);
        $stmt -> execute();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        
        //データベース内のメールアドレスと重複していない場合、登録する。
        if ($error_message == '') {
            if (!isset($row['username'])) {
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUE (:username, :password)");
                $stmt -> bindParam(':username', $username, PDO::PARAM_STR);
                $stmt -> bindParam(':password', $password, PDO::PARAM_STR);
                $stmt -> execute();
                
                header('Location: login.php');
                exit;
            } else {
                $error_message = 'そのユーザ名は使用できません。';
            }
        }
    }

?>

<body>
    <?php include './common/header.php'; ?>
    <div class="container">
        <h2 class="text-center mt-5">新規登録</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label">ユーザ名</label>
                <input type="text" name="username" class="form-control" required>
                <div class="form-text">半角英数字, _ のみ</div>
          </div>
          <div class="mb-3">
            <label class="form-label">パスワード</label>
            <input type="password" name="password" class="form-control" required>
            <div class="form-text">半角英数字をそれぞれ1文字以上含み、8文字以上</div>
          </div>
          <div class="mb-3">
            <label class="form-label">確認用パスワード</label>
            <input type="password" name="valid_password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary me-3">登録</button>
          <span class="text-danger"><?= $error_message ?></span>
        </form>
    </div>
</body>
</html>