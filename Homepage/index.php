<?php
    require '../functions.php';

    $err_msg = '';

    session_start();

    if(!isset($_SESSION['user'])){
        header('location: ../Login');
    }

    $err_msg = isset($_GET['err_msg']) ? $_GET['err_msg'] : '';

    try {
        [$retval, $corsi_utente]  = get_corsi_utente($_SESSION['user']);
    } catch (Exception $e) {
        $err_msg = $e->getMessage();
    }

    if (!$retval) {
        $err_msg = $corsi_utente;
    } else {

        $html_table = '<div id="corsi">';

        foreach($corsi_utente as $corso){
            $html_table .= '<div class="corso">';
            $html_table .= '<h3>' . $corso['Nome'] . '</h3>';
            $html_table .= '<br>';
            $html_table .= '<div id="linksConatiner">';
            $html_table .= '<button class=links><a href="../PlayFlashCards?corso=' . urlencode($corso['IDCorso']) . '">Gioca FlashCards</a></button>';
            $html_table .= '<button class=links><a href="../PlayMemory?corso=' . urlencode($corso['IDCorso']) . '" >Gioca Memory</a></button>';
            $html_table.= '<button class=links><a href="../Disiscriviti?corso=' . urlencode($corso['IDCorso']) . '" >Disiscriviti</a></button>';
            $html_table .= '</div>';
            $html_table .= '</div>';
        }

        $html_table .= '</div>';
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Homepage</title>
</head>
<body>
    <header>
        <h1>Homepage</h1>
        <a href="../Libreria">Vai alla libreria</a>
        <a href="../Profilo">Vai al profilo</a>
        <a href="../CaricaCards">Carica Cards</a>
    </header>
    <br><br>
    <h1>Benvenuto <?=$_SESSION['user']?></h1>
    <br><br>
    <h2>I tuoi corsi:</h2>
    <?=$err_msg != '' ? $err_msg : $html_table?>
</body>
</html>

