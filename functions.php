<?php 
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'CRUD');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'FreeFlash');


function login_check($username, $password) {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    $query = "SELECT * FROM Utenti WHERE Username = ? AND Password = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['user'] = $username;
        return [true, "Login Effettuato"];
    } else {
        return [false, "Nome utente o password non validi"];
    } 
}


function logout() {
    session_destroy();
    header('Location: ../Login');
}

function register_user($username, $password, $email) {

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }
    
    $query = "SELECT * FROM Utenti WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return [false, "Nome utente già esistente"];
    } else {
        $query = "INSERT INTO Utenti (username, Password, Email) VALUES ('$username', '$password', '$email')";
        if (mysqli_query($conn, $query)) {
            return [true, "Utente registrato con successo"];
        } else {
            return [false, "Errore durante la registrazione"];
        }
    }

}

function crea_corso($nome_corso, $username, $lingua) {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    $query = "SELECT * FROM Corsi WHERE Nome = '$nome_corso'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return [false, "Corso già esistente"];
    }
    
    $query = "INSERT INTO Corsi (Nome, IDUtente, IDLingua) VALUES ('$nome_corso', (SELECT IDUtente FROM Utenti WHERE Username = '$username'), (SELECT IDLingua FROM Lingue WHERE Nome = '$lingua'))";
    if (mysqli_query($conn, $query)) {
        return [true, "Corso creato con successo"];
    } else {
        return [false, "Errore durante la creazione del corso"];
    }
}

function get_corsi() {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }
    
    $query = "SELECT * FROM Corsi";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $corsi[] = $row;
    }
    
    if (mysqli_num_rows($result) > 0) {
        return [true, $corsi];
    } else {
        return [false, "Nessun corso disponibile"];
    }
}


function get_libreria_corsi($username) {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    //seleziona tutti i corsi a cui l'utente non è iscritto
    $query = "
        SELECT c.* 
        FROM Corsi c
        LEFT JOIN Iscrizioni i ON i.IDCorso = c.IDCorso AND i.IDUtente = (SELECT IDUtente FROM Utenti WHERE Username = '$username')
        WHERE i.IDUtente IS NULL OR i.IDUtente != (SELECT IDUtente FROM Utenti WHERE Username = '$username')
    ";


    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $corsi[] = $row;
    }
    
    if (mysqli_num_rows($result) > 0) {
        return [true, $corsi];
    } else {
        return [false, "Nessun corso disponibile"];
    }
}

function get_corsi_utente() {

    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    $query = "SELECT c.* FROM Corsi c, Iscrizioni i, Utenti u WHERE c.IDCorso = i.IDCorso AND i.IDUtente = u.IDUtente AND u.Username = '".$_SESSION['user']."'";
    $result = mysqli_query($conn, $query);

    $corsi = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $corsi[] = $row;
    }

    if (mysqli_num_rows($result) > 0) {
        return [true, $corsi];
    } else {
        return [false, "Utente non iscritto a nessun corso"];
    }
}

function iscrivi_utente_corso($corso) {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    // Proteggere la query dall'SQL Injection
    $username = mysqli_real_escape_string($conn, $_SESSION['user']);
    $corso = mysqli_real_escape_string($conn, $corso);

    // Controllare se l'utente è già iscritto
    $query = "SELECT * FROM Iscrizioni 
              WHERE IDUtente = (SELECT IDUtente FROM Utenti WHERE Username = '$username') 
              AND IDCorso = $corso";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        return [false, "Utente già iscritto a questo corso"];
    }

    // Iscrivere l'utente al corso
    $query = "INSERT INTO Iscrizioni (IDUtente, IDCorso) 
              VALUES (
                  (SELECT IDUtente FROM Utenti WHERE Username = '$username'), 
                  $corso
              )";

    if (mysqli_query($conn, $query)) {
        return [true, "Utente iscritto al corso con successo"];
    } else {
        return [false, "Errore durante l'iscrizione: " . mysqli_error($conn)];
    }
}

function disiscrivi_utente($corso, $username) {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    $query = "DELETE FROM Iscrizioni WHERE IDUtente = (SELECT IDUtente FROM Utenti WHERE Username = '$username') AND IDCorso = $corso";
    if (mysqli_query($conn, $query)) {
        return [true, "Utente disiscritto con successo"];
    } else {
        return [false, "Errore durante la disiscrizione"];
    }
}


function carica_card($foreign_word, $native_word, $corso) {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }
    
    $query = "SELECT * FROM Cards WHERE ForeignWord = '$foreign_word' AND NativeWord = '$native_word' AND IDCorso = (SELECT IDCorso FROM Corsi WHERE Nome = '$corso')";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return [false, "Carta già esistente"];
    } else {
        $query = "INSERT INTO Cards (ForeignWord, NativeWord, IDCorso) VALUES ('$foreign_word', '$native_word', (SELECT IDCorso FROM Corsi WHERE Nome = '$corso'))";
        if (mysqli_query($conn, $query)) {
            return [true, "Carta creata con successo"];
        } else {
            return [false, "Errore durante la creazione della carta"];
        }
    }
}

function crea_mazzo($corso) {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    if (!isset($_SESSION['user'])) {
        throw new Exception("Sessione utente non valida.");
    }

    $mazzo = [];
    $parole_usate = []; // Array per tenere traccia delle parole già inserite nel mazzo

    // **1️⃣ Seleziona prima le carte pronte per la revisione dalla tabella visione**
    $query = "
        SELECT c.IDCard, c.ForeignWord, c.NativeWord, v.IDVisione
        FROM Visione v
        JOIN Cards c ON v.IDCard = c.IDCard
        WHERE v.IDUtente = (SELECT IDUtente FROM Utenti WHERE Username = ?)
        AND TIMESTAMPDIFF(MINUTE, v.LastSeen, NOW()) >= v.MinutesToPass
        AND c.IDCorso = ?
        ORDER BY RAND() LIMIT 30";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $_SESSION['user'], $corso);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        if (!isset($parole_usate[$row['ForeignWord']])) {
            $parole_usate[$row['ForeignWord']] = true;
            $mazzo[] = $row;
        }
    }

    // **2️⃣ Se il mazzo ha meno di 30 carte, prendi nuove carte dalla tabella cards**
    if (count($mazzo) < 30) {
        $carte_mancanti = 30 - count($mazzo);

        // Seleziona nuove carte evitando quelle già presenti nella tabella visione per l'utente
        $query = "
            SELECT c.IDCard, c.ForeignWord, c.NativeWord 
            FROM Cards c
            WHERE c.IDCorso = ?
            AND c.IDCard NOT IN (
                SELECT v.IDCard FROM Visione v 
                WHERE v.IDUtente = (SELECT IDUtente FROM Utenti WHERE Username = ?)
            )
            ORDER BY RAND()";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "is", $corso, $_SESSION['user']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_assoc($result)) {
            if (count($mazzo) >= 30) {
                break;
            }
            if (!isset($parole_usate[$row['ForeignWord']])) {
                $parole_usate[$row['ForeignWord']] = true;
                $mazzo[] = $row;
            }
        }
    }

    return [!empty($mazzo), $mazzo];
}



//function visualizza_mazzo($mazzo[])

function update_cards($card, $timeToPass) {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        throw new Exception("Errore di connessione al database");
    }

    // Verifica se esiste già un record nella tabella visione con lo stesso IDCard e IDUtente
    $query = "SELECT * FROM Visione 
              WHERE IDUtente = (SELECT IDUtente FROM Utenti WHERE Username = ?) 
              AND IDCard = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $_SESSION['user'], $card['IDCard']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Determina il valore di minutesToPass e nOfConsecutiveEasy
        if ($timeToPass == 30) {
            // Se il tempo da far passare è 30, incrementa nOfConsecutiveEasy
            $nOfConsecutiveEasy = $row['nOfConsecutiveEasy'] + 1;
            // Se nOfConsecutiveEasy raggiunge 3, azzera e imposta minutesToPass a 4320
            if ($nOfConsecutiveEasy == 3) {
                $nOfConsecutiveEasy = 0;
                $timeToPass = 10080;
            }
        } else {
            // Altrimenti, lascia invariato nOfConsecutiveEasy
            $nOfConsecutiveEasy = $row['nOfConsecutiveEasy'];
        }
    
        // Prepara la query per aggiornare lastSeen, minutesToPass e nOfConsecutiveEasy
        $query = "UPDATE Visione 
                  SET LastSeen = NOW(), MinutesToPass = ?, nOfConsecutiveEasy = ? 
                  WHERE IDVisione = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iii", $timeToPass, $nOfConsecutiveEasy, $row['IDVisione']);
        $result = mysqli_stmt_execute($stmt);
        
        if (!$result) {
            return [false, "Errore durante l'aggiornamento della carta ID ".$card['IDCard']];
        }
    
    } else {
        // Se non esiste un record, inserisci uno nuovo con IDUtente, IDCard, lastSeen, minutesToPass e nOfConsecutiveEasy
        // Se timeToPass è 30, imposta nOfConsecutiveEasy a 1, altrimenti a 0
        $nOfConsecutiveEasy = ($timeToPass == 30) ? 1 : 0;

        $query = "INSERT INTO Visione (IDUtente, IDCard, LastSeen, MinutesToPass, nOfConsecutiveEasy) 
                  VALUES ((SELECT IDUtente FROM Utenti WHERE Username = ?), ?, NOW(), ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "siii", $_SESSION['user'], $card['IDCard'], $timeToPass, $nOfConsecutiveEasy);
        if (!mysqli_stmt_execute($stmt)) {
            return [false, "Errore durante l'inserimento della visione per la carta ID ".$card['IDCard']];
        }
    }

    return [true, "Carte aggiornate con successo"];
}



?>