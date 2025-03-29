CREATE DATABASE FreeFlash;
USE FreeFlash;
DROP USER IF EXISTS 'CRUD'@'localhost';
CREATE USER 'CRUD'@'localhost' IDENTIFIED BY 'password';
GRANT SELECT, INSERT, UPDATE, DELETE ON FreeFlash.* TO 'CRUD'@'localhost';

CREATE TABLE IF NOT EXISTS Utenti (
    IDUtente INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(255) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Lingue (
    IDLingua INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS Corsi (
    IDCorso INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255) NOT NULL UNIQUE,
    Descrizione VARCHAR(500),
    IDUtente INT NOT NULL,
    IDLingua INT NOT NULL,
    FOREIGN KEY (IDLingua) REFERENCES Lingue(IDLingua),
    FOREIGN KEY (IDUtente) REFERENCES Utenti(IDUtente)
);

CREATE TABLE IF NOT EXISTS Cards (
    IDCard INT AUTO_INCREMENT PRIMARY KEY,
    ForeignWord VARCHAR(500) NOT NULL,
    NativeWord VARCHAR(500) NOT NULL,
    IDCorso INT NOT NULL,
    FOREIGN KEY (IDCorso) REFERENCES Corsi(IDCorso)
);

CREATE TABLE IF NOT EXISTS Visione (
    IDVisione INT AUTO_INCREMENT PRIMARY KEY,
    IDUtente INT NOT NULL,
    IDCard INT NOT NULL,
    LastSeen TIMESTAMP NOT NULL,
    MinutesToPass INT NOT NULL,
    nOfConsecutiveEasy INT DEFAULT 0,
    FOREIGN KEY (IDUtente) REFERENCES Utenti(IDUtente),
    FOREIGN KEY (IDCard) REFERENCES Cards(IDCard)
);

CREATE TABLE IF NOT EXISTS Iscrizioni (
    IDIscrizione INT AUTO_INCREMENT PRIMARY KEY,
    IDUtente INT NOT NULL,
    IDCorso INT NOT NULL,
    FOREIGN KEY (IDUtente) REFERENCES Utenti(IDUtente),
    FOREIGN KEY (IDCorso) REFERENCES Corsi(IDCorso)
);

INSERT INTO Lingue (Nome) VALUES 
('Italiano'),
('Inglese'),
('Francese'),
('Spagnolo'),
('Tedesco'),
('Portoghese'),
('Russo'),
('Cinese'),
('Giapponese'),
('Coreano'),
('Arabo'),
('Turco'),
('Olandese'),
('Svedese'),
('Danese'),
('Norvegese'),
('Finlandese'),
('Polacco'),
('Ceco'),
('Ungherese'),
('Rumeno');

INSERT INTO Corsi (Nome, IDUtente, IDLingua) VALUES 
('Spanish 1', 1, 4),
('French 1', 1, 2),
('German 1', 1, 5),
('Italian 1', 1, 1),
('Russian 1', 1, 7),
('Chinese 1', 1, 8),
('Japanese 1', 1, 9),
('Korean 1', 1, 10),
('Arabic 1', 1, 11),
('Turkish 1', 1, 12),
('Dutch 1', 1, 13),
('Swedish 1', 1, 14),
('Danish 1', 1, 15),
('Norwegian 1', 1, 16),
('Finnish 1', 1, 17),
('Polish 1', 1, 18),
('Czech 1', 1,19),
('Hungarian 1', 1,20),
('Romanian 1', 1,21);
