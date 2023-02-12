<?php
    session_start();
    require_once('../config/config.php');
    
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $stmt = $pdo->prepare("DELETE FROM things WHERE id=:id AND user_id=:user_id");
    $stmt -> bindParam(':id', $_POST['delete_id'], PDO::PARAM_INT);
    $stmt -> bindParam(':user_id', $_SESSION['USERID'], PDO::PARAM_INT);
    $stmt -> execute();
    
    header('Location: ../home.php');
    exit;
?>