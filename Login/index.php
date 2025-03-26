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
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>
<body>

<div id="container">
    <h1>Login</h1>

    <!-- Display the error message, if any -->
    <h4><?= htmlspecialchars($err_msg) ?> </h4> <!-- Use htmlspecialchars for security -->

    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="text" id="username" name="username" placeholder="Username" required>
        <br><br>
        
        <input type="password" id="password" name="password" placeholder="Password" required>
        <br><br>
        <input type="submit" value="Login">
    </form>

    <a href="../Register">Registrati</a>
</div>
</body>
</html>
