<?php
    session_start();
    require_once('../config/config.php');
    
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $sql = 'UPDATE things SET ';
    if ($_POST['done_button'] == 'to_done') {
        $sql .= 'is_done=1 ';
    } else {
        $sql .= 'is_done=0 ';
    }
    $sql .= 'WHERE id=:id AND user_id=:user_id';
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(':id', $_POST['thing_id'], PDO::PARAM_INT);
    $stmt -> bindParam(':user_id', $_SESSION['USERID'], PDO::PARAM_INT);
    $stmt -> execute();
    
    header('Location: ../home.php');
    exit;
?>