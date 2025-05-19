<?php
    session_start();
    if (!isset($_SESSION['tuvastamine'])) {
        header('Location: ../login.php');
        exit();
    } 

    // var_dump($_SESSION['tuvastamine']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="../logout.php">Logi välja</a>
    <h1>Salajane</h1>
</body>
</html>