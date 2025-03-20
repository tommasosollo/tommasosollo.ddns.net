<?php

require '../functions.php';

session_start();

if(!isset($_SESSION['user'])){
    //header('location: ../Login');
}

$corso = $_GET['corso'];

try {
    [$retval, $retmsg] = iscrivi_utente_corso($corso);
} catch (Exception $e) {
    //$err_msg = "Errore nell'iscrizione, riprovare piu tardi";
    $err_msg = $e->getMessage();
    header('Location: ../Libreria?err_msg=' . urlencode($err_msg));
    exit;
}

if ($retval) {
    header('Location: ../Libreria');
    exit;
} else {
    if ($retmsg) {
        header('Location: ../Libreria?err_msg=' . urlencode($retmsg));
    }else {
        header('Location: ../Libreria');
    }
}

?>
