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
    <title>Profilo</title>
</head>
<body>
    <h1>Ciao <?=$_SESSION['user']?></h1>
    <br><br>
    <a href="../Homepage">Homepage</a>
    <br>
    <a href="../Logout">Logout</a>

</body>
</html>