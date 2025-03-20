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

        $html_table = '<table>';

        foreach($corsi_utente as $corso){
            $html_table .= '<tr>';
            $html_table .= '<td>' . $corso['Nome'] . '</td>';
            $html_table .= '<td><a href="../PlayFlashCards?corso=' . urlencode($corso['IDCorso']) . '">Gioca</a></td>';
            $html_table.= '<td><a href="../Disiscriviti?corso=' . urlencode($corso['IDCorso']) . '">Disiscriviti</a></td>';
            $html_table .= '</tr>';
        }

        $html_table .= '</table>';
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
</head>
<body>
    <h1>Benvenuto <?=$_SESSION['user']?></h1>
    <br><br>
    <h2>I tuoi corsi:</h2>
    <?=$err_msg != '' ? $err_msg : $html_table?>
    <br><br>
    <a href="../Libreria">Vai alla libreria</a>
    <br><br>
    <a href="../Profilo">Vai al profilo</a>
    <br><br>
    <a href="../CaricaCards">Carica Cards</a>
</body>
</html>

