<?php
    session_start();
    require_once('../config/config.php');
    
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
    $stmt = $pdo->prepare("INSERT INTO things (name, amount, unit, detail, deadline, user_id) VALUE (:name, :amount, :unit, :detail, :deadline, :user_id)");
    $stmt -> bindParam(':name', $_POST['name'], PDO::PARAM_STR);
    $stmt -> bindParam(':amount', $_POST['amount'], PDO::PARAM_INT);
    $stmt -> bindParam(':unit', $_POST['unit'], PDO::PARAM_STR);
    $stmt -> bindParam(':detail', $_POST['detail'], PDO::PARAM_STR);
    if ($_POST['deadline'] != '') {
        $stmt -> bindParam(':deadline', $_POST['deadline'], PDO::PARAM_STR);
    } else {
        // 空白の場合は NULL を挿入
        $stmt->bindValue(":deadline", null, PDO::PARAM_NULL);
    }
    $stmt -> bindParam(':user_id', $_SESSION['USERID'], PDO::PARAM_INT);
    $stmt -> execute();
    
    header('Location: ../home.php');
    exit;
?>