CREATE DATABASE IF NOT EXISTS fastroute;
USE fastroute;

-- Tabella Utente
CREATE TABLE Utente (
                        ID INT AUTO_INCREMENT PRIMARY KEY,
                        Nome VARCHAR(50) NOT NULL,
                        Cognome VARCHAR(50) NOT NULL,
                        Email VARCHAR(100) NOT NULL UNIQUE,
                        Password VARCHAR(255) NOT NULL
);

-- Tabella Cliente (Mittenti/Destinatari)
CREATE TABLE Cliente (
                         ID INT AUTO_INCREMENT PRIMARY KEY,
                         Nome VARCHAR(50) NOT NULL,
                         Cognome VARCHAR(50) NOT NULL,
                         UNIQUE KEY unique_cliente (Nome, Cognome) -- Evita duplicati
);

-- Tabella Spedizione
CREATE TABLE Spedizione (
                            ID INT AUTO_INCREMENT PRIMARY KEY,
                            CodiceUnivoco VARCHAR(20) NOT NULL UNIQUE, -- Codice univoco inserito manualmente
                            MittenteID INT NOT NULL,
                            DestinatarioID INT NOT NULL,
                            Stato ENUM('in partenza', 'in transito', 'consegnato') NOT NULL DEFAULT 'in partenza',
                            DataSpedizione DATETIME NOT NULL,
                            DataRitiro DATETIME DEFAULT NULL,
                            DataConsegna DATETIME DEFAULT NULL,
                            FOREIGN KEY (MittenteID) REFERENCES Cliente(ID),
                            FOREIGN KEY (DestinatarioID) REFERENCES Cliente(ID)
);