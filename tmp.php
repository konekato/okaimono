<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>TMP | APP</title>


    <?php
        session_start();
        if (empty($_SESSION['USERNAME'])){
            $tmp = 'None';
        } else {
            $tmp = $_SESSION['USERNAME'];
        }
    ?>
</head>
<body>
    <div style="padding: 0 3rem 1rem 3rem">
        <h2><?= $tmp ?></h2>
    </div>
</body>
</html>