<?php

require '../functions.php';

session_start();

if(!isset($_SESSION['user'])){
    header('location: ../Login');
}

$err_msg = '';

$err_msg = isset($_GET['err_msg']) ? $_GET['err_msg'] : '';


$html_table = '<div id="corsi">';

try {
    [$retval, $corsi] = get_libreria_corsi($_SESSION['user']);
} catch (Exception $e) {
    $err_msg = $e->getMessage();
}

if (!$retval) {
    $err_msg = $corsi;
} else {
    foreach ($corsi as $corso) {
        $html_table .= '<div class="corso">';
        $html_table .= '<h3>' . $corso['Nome'] . '</h3>';
        $html_table .= '<br>';
        $html_table .= '<td><a href="../IscriviUtente?corso=' . urlencode($corso['IDCorso']) . '" class="links">Iscriviti al corso</a></td>';
        $html_table .= '</div>';            
    }
}

$html_table.= '</div>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Libreria Corsi</title>
</head>
<body>
    <header>
        <h1>Libreria Corsi</h1>
        <a href="../Homepage">Vai alla Homepage</a>
        <a href="../Profilo">Vai al profilo</a>
        <a href="../CaricaCards">Carica Cards</a>
    </header>
    <br><br>
        
    

    <h2>Corsi Disponibili</h2>
    <?=$err_msg != '' ? $err_msg : $html_table?>

    <br><br>

</body>
</html>
