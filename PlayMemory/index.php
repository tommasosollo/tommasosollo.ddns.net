<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page if not logged in
    header("Location: ../Login");
    exit();
}

require '../functions.php';


?>

<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Memory</title>
    <link rel="stylesheet" href="memory.css" />
  </head>
  <body>
    <div id="container">
      <h2>Errori: <span id="errors">0</span></h2>

      <div id="board"></div>
    </div>
  </body>
  <script src="memory.js"></script>
</html>
