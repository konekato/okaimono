<?php
    $title = 'ログイン';
    include './common/head.php'; // head.php の読み込み
?>

<?php
    session_start();
    if (!empty($_SESSION['USERNAME'])) {
        header('Location: home.php');
        exit;
    }
    
    $error_message = '';
    
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        require_once('./config/config.php');
    
        $pdo = new PDO(DSN, DB_USER, DB_PASS);
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt -> bindParam(':username', $username, PDO::PARAM_STR);
        $stmt -> execute();
        $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        
        if (isset($row['password']) && password_verify($password, $row['password'])) {
            session_regenerate_id();
            $_SESSION['USERID'] = $row['id'];
            $_SESSION['USERNAME'] = $row['username'];
            
            header('Location: home.php');
            exit;
        } else {
            $error_message = 'メールアドレス又はパスワードが間違っています。';
        }
        
    }

?>

<body>
    <?php include './common/header.php'; ?>
    <div class="container">
        <h2 class="text-center mt-5">ログイン</h2>
        <form action="" method="post">
          <div class="mb-3">
            <label class="form-label">ユーザ名</label>
            <input type="text" name="username" required class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">パスワード</label>
            <input type="password" name="password" required class="form-control">
          </div>
          <button type="submit" class="btn btn-primary me-3">ログイン</button>
          <span class="text-danger"><?= $error_message ?></span>
        </form>
        <div class="mt-3">登録がまだの方は<a href="register.php">こちら</a></div>
    </div>
</body>
</html>