<?php

require '../functions.php';

session_start();

$err_msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    try {
        [$retval, $retmsg] = register_user($username, $password, $email);
    } catch (Exception $e) {
        $err_msg = $e->getMessage();
    }

    if ($retval) {
        header('Location: ../Homepage');
        exit;
    } else {
        $err_msg = $retmsg;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Registrati</title>
</head>
<body>

<div id="container">
<h1>Registrati</h1>

<h4><?=$err_msg?></h4>

<form action="<?=$_SERVER["PHP_SELF"]?>" method="post">
    <input type="text" id="username" name="username" pattern=".{3,}" placeholder="Username" required>
    <br><br>
    <input type="email" id="email" name="email" pattern=".{3,}" placeholder="Email" required>
    <br><br>
    <input type="password" id="password" name="password" pattern=".{3,}" placeholder="Password" required>
    <br><br>
    <input type="submit" value="Registrati">
</form>

<a href="../Login">Login</a>
</div>
</body>
</html>