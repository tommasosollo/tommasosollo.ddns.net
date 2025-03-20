<?php

require '../functions.php';

session_start();

if(!isset($_SESSION['user'])){
    header('location: ../Login');
}

$err_msg = '';

$err_msg = isset($_GET['err_msg']) ? $_GET['err_msg'] : '';


$html_table = '<table>';

try {
    [$retval, $corsi] = get_libreria_corsi($_SESSION['user']);
} catch (Exception $e) {
    $err_msg = $e->getMessage();
}

if (!$retval) {
    $err_msg = $corsi;
} else {
    foreach ($corsi as $corso) {
        $html_table .= '<tr>';
        $html_table .= '<td>' . $corso['Nome'] . '</td>';
        $html_table .= '<td><a href="../IscriviUtente?corso=' . urlencode($corso['IDCorso']) . '">Iscriviti al corso</a></td>';
        $html_table .= '</tr>';
    }
}

$html_table.= '</table>';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libreria Corsi</title>
</head>
<body>
    
<h1>Libreria Corsi</h1>

<h2>Corsi Disponibili</h2>
<?=$err_msg != '' ? $err_msg : $html_table?>

<br><br>
<a href="../Homepage">Homepage</a>
</body>
</html>
