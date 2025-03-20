<?php
require '../functions.php';

session_start();

if(!isset($_SESSION['user'])){
    header('location:../Login');
}

try {
    [$retval, $retmsg] = disiscrivi_utente($_GET['corso'], $_SESSION['user']);
} catch (Exception $e) {
    $err_msg = "Errore durante la disiscrizione, riprova piu tardi ";
}

if (!$retval) {
    $err_msg = $retmsg;
    header('location:../Homepage?err_msg='.$err_msg);
} else {
    header('location:../Homepage');
}



?>