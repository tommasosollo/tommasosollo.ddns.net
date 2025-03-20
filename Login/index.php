<?php
require '../functions.php';

session_start();

if (isset($_SESSION['user'])) {
    header('Location: ../Homepage');
    exit;  // Ensure no further code is executed after redirect
}

$err_msg = '';  // Initialize the error message
$retmsg = '';   // Initialize retmsg to avoid "not defined" error
$retval = false;  // Initialize retval to avoid "not defined" error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Ensure login_check returns both $retval and $retmsg
        [$retval, $retmsg] = login_check($username, $password);
    } catch (Exception $e) {
        $err_msg = $e->getMessage();
        // You may want to prevent further code execution if an exception occurs
    }

    if ($retval && $retval !== null) {
        // If login is successful, redirect to the homepage
        header('Location: ../Homepage');
        exit;
    } else {
        // Display the error message if login failed
        $err_msg = $retmsg;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<!-- Display the error message, if any -->
<h4><?= htmlspecialchars($err_msg) ?> </h4> <!-- Use htmlspecialchars for security -->

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>
    <input type="submit" value="Login">
</form>

<a href="../Register">Registrati</a>

</body>
</html>
