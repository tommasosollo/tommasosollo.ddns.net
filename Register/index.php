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
    <title>Registrati</title>
</head>
<body>
    
<h1>Registrati</h1>

<h4><?=$err_msg?></h4>

<form action="<?=$_SERVER["PHP_SELF"]?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" pattern=".{3,}" required>
    <br><br>
    <label for="username">Email:</label>
    <input type="email" id="email" name="email" pattern=".{3,}" required>
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" pattern=".{3,}" required>
    <br><br>
    <input type="submit" value="Registrati">
</form>

<a href="../Login">Login</a>
</body>
</html>