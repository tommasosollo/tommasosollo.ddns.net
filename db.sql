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

CREATE TABLE IF NOT EXISTS Cards (
    IDCard INT AUTO_INCREMENT PRIMARY KEY,
    ForeignWord VARCHAR(500) NOT NULL,
    NativeWord VARCHAR(500) NOT NULL,
    IDLingua INT NOT NULL,
    FOREIGN KEY (IDLingua) REFERENCES Lingue(IDLingua)
);

CREATE TABLE IF NOT EXISTS Visione (
    INT AUTO_INCREMENT PRIMARY KEY,
    IDUtente INT NOT NULL,
    IDCard INT NOT NULL,
    LastSeen TIMESTAMP NOT NULL,
    MinutesToPass INT NOT NULL,
    nOfConsecutiveEasy INT DEFAULT 0,
    FOREIGN KEY (IDUtente) REFERENCES Utenti(IDUtente),
    FOREIGN KEY (IDCard) REFERENCES Cards(IDCard)
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
('Francese');
