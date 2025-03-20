<?php
require '../functions.php';

session_start();

if(!isset($_SESSION['user'])){
    header('location: ../Login');
}

try {
logout();
} catch (Exception $e) {
    echo "Errore, riprova piu tardi";
}
?>

