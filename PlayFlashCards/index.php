<?php

require '../functions.php';

session_start();

if(!isset($_SESSION['user'])){
    header('location:../Login');
}

$err_msg = '';

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['corso'])) {
    $corso = $_GET['corso'];

    try {
        [$retval, $flashcards] = crea_mazzo($corso);
    } catch (Exception $e) {
        $err_msg = "Errore nel caricamento del mazzo, riprova piu tardi.";
        $retval = false;
    }

    if (!$retval || empty($flashcards)) {
        $err_msg = "Errore: Nessuna flashcard trovata.";
        $html_card = "";
    }    
    else {
        $_SESSION['flashcards'] = $flashcards;
        $_SESSION['current_card'] = 0;

        $card = $flashcards[$_SESSION['current_card']];
        $html_card = '<div class="card">';
        $html_card .= '<h1 id="foreignWord">' . $card['ForeignWord'] . '</h1>';
        $html_card .= '<h2 id="answer" style="display:none">' . $card['NativeWord'] . '</h2>'; 
        $html_card .= '<button onclick="showAnswer()" >Mostra Risposta</button>';       
        $html_card .= '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';
        $html_card .= '<button type="submit" name="Risposta" value="1">1 Minute (Very Hard)</button>';
        $html_card .= '<button type="submit" name="Risposta" value="5">5 Minutes (Hard)</button>';
        $html_card .= '<button type="submit" name="Risposta" value="30">30 Minutes (Easy)</button>';
        $html_card .= '</form>';
        $html_card.= '</div>';
    }
} else {

    $cardToUpdate = $_SESSION['flashcards'][$_SESSION['current_card']];
    update_cards($cardToUpdate, $_POST['Risposta']);

    $_SESSION['current_card']++;

    if ($_SESSION['current_card'] >= count($_SESSION['flashcards'])) {
        $err_msg = "Fine delle flashcards!";
        $html_card = "";
    } else {
        $card = $_SESSION['flashcards'][$_SESSION['current_card']];
        $html_card = '<div class="card">';
        $html_card .= '<h1 id="foreignWord">' . $card['ForeignWord'] . '</h1>';
        $html_card .= '<h2 id="answer" style="display:none">' . $card['NativeWord'] . '</h2>';   
        $html_card .= '<button onclick="showAnswer()" >Mostra Risposta</button>';     
        $html_card .= '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';
        $html_card .= '<button type="submit" name="Risposta" value="1">1 Minute</button>';
        $html_card .= '<button type="submit" name="Risposta" value="5">5 Minutes</button>';
        $html_card .= '<button type="submit" name="Risposta" value="30">30 Minutes</button>';
        $html_card .= '</form>';
        $html_card .= '</div>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Play FlashCards</title>
</head>
<body>
    <h1>Play FlashCards</h1>
    <?=$err_msg != '' ? $err_msg : $html_card?>
    <br><br>
    <a href="../Homepage">Torna alla Homepage</a>
</body>
</html>

<script src="script.js"></script>