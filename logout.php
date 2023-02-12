<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        session_start();
        
        $_SESSION = array();
        
        if (isset($_COOKIE["PHPSESSID"])) {
            setcookie("PHPSESSID", '', time() - 1800, '/');
        }
        
        session_destroy();
        
        header('Location: login.php');
        exit;
    } else {
        header('Location: home.php');
        exit;
    }
?>