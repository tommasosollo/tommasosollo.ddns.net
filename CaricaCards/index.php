<?php

require '../functions.php';

session_start();

if(!isset($_SESSION['user'])){
    header('location:../Login');
    exit;
}

$err_msg = '';
$html_form = '';

// Form di caricamento del file
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $html_form = '<form action="' . $_SERVER['PHP_SELF'] . '" method="post" enctype="multipart/form-data">';
    $html_form .= '<label for="fileToUpload">Seleziona il file CSV</label>';
    $html_form .= '<input type="file" name="fileToUpload" id="fileToUpload" accept=".csv">';
    $html_form .= '<br>';
    $html_form .= '<br>';
    $html_form .= '<label for="corso">Corso:</label>';
    $html_form .= '<select name="corso" id="corso">';
    
    // Recupera i corsi da database
    try {
        [$retval, $corsi] = get_corsi();
    } catch (Exception $e) {
        $err_msg = $e->getMessage();
    }

    if (!$retval) {
        $err_msg = $corsi;
    } else {
        foreach ($corsi as $corso) {
            $html_form .= '<option value="' . $corso['Nome'] . '">' . $corso['Nome'] . '</option>';
        }
    }

    $html_form .= '</select>';
    $html_form .= '<br>';
    $html_form .= '<input type="submit" value="Carica" name="submit">';
    $html_form .= '</form>';
}

// Gestione della logica per il caricamento del file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controlla se il file è stato caricato correttamente
    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
        $fileTmpPath = $_FILES['fileToUpload']['tmp_name'];
        
        // Verifica che il file sia un CSV
        $fileType = mime_content_type($fileTmpPath);
        if ($fileType == 'text/plain' || $fileType == 'application/csv' || pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION) == 'csv') {
            // Leggi il contenuto del file CSV
            $fileContent = file_get_contents($fileTmpPath);
            $rows = explode("\n", $fileContent);
            
            foreach ($rows as $row) {
                // Escludi le righe vuote
                if (empty($row)) continue;
                
                // Divide ogni riga per la virgola
                $word = explode(",", $row);
                if (count($word) >= 2) {
                    $foreignWord = trim($word[0]);
                    $nativeWord = trim($word[1]);
                    $corso = $_POST['corso'];              
                    $err_msg = '';
                    try {
                        // Chiama la funzione per caricare la card
                        [$retval, $retmsg] = carica_card($foreignWord, $nativeWord, $corso);
                    } catch (Exception $e) {
                        $err_msg = $e->getMessage();
                        continue; // Continua con la prossima parola se c'è un errore
                    }
                    
                    if (!$retval) {
                        // Se ci sono errori, memorizza il messaggio
                        $err_msg .= $retmsg . '<br>';
                    }
                }
            }
            
            if (empty($err_msg)) {
                $err_msg = "Caricamento completato con successo.";
            }
        } else {
            $err_msg = "Errore: il file caricato non è un CSV valido.";
        }
    } else {
        $err_msg = "Errore durante il caricamento del file.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Carica Cards</title>
</head>
<body>
    <header>
        <h1>Carica Cards</h1>
        <a href="../Homepage">Vai alla Homepage</a>
        <a href="../Libreria">Vai alla libreria</a>
        
    </header>
    <br><br>
    
    <?=$err_msg != '' ? $err_msg : $html_form?>

</body>
</html>
