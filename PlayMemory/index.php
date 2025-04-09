<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to the login page if not logged in
    header("Location: ../Login");
    exit();
}

require '../functions.php';

$corso = $_GET['corso'] ?? null;
if(!$corso) {
    // Redirect to the login page if not logged in
    header("Location: ../Login");
    exit();
}

[$retval, $cardsArray] = crea_mazzo($corso, 10); 
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
    <header>
        <h1>Play Memory</h1>
        <a href="../Homepage">Vai alla Homepage</a>
        <a href="../Profilo">Vai al profilo</a>
    </header>
  
    <div id="CardsContainer">
      <h2>Errori: <span id="errors">0</span></h2>

      <div id="board"></div>
    </div>
    <button id="button">Restart</button>
  </body>
  <script>
  const wordPairs = <?php echo json_encode($cardsArray); ?>;
  const corso = <?php echo $corso ?>;
</script>
<script src="memory.js"></script>
</html>
