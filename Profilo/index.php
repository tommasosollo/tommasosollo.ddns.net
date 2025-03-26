<?php
    session_start();
    if(!isset($_SESSION['user'])){
        header('Location: ../Login');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Profilo</title>
</head>
<body>
    <header>
        <h1>Ciao <?=$_SESSION['user']?></h1>
            <a href="../Homepage">Homepage</a>
            <a href="../Logout">Logout</a>
    </header>
    <br><br>

</body>
</html>