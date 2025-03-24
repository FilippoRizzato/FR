-- Tabella Utente
CREATE TABLE Utente (
                        ID INT AUTO_INCREMENT PRIMARY KEY,
                        Nome VARCHAR(50) NOT NULL,
                        Cognome VARCHAR(50) NOT NULL,
                        Email VARCHAR(100) NOT NULL UNIQUE,
                        Password VARCHAR(255) NOT NULL,
                        TemaColore VARCHAR(20) DEFAULT 'default' -- Tema di colore preferito
);

-- Tabella Cliente
CREATE TABLE Cliente (
                         ID INT AUTO_INCREMENT PRIMARY KEY,
                         Nome VARCHAR(50) NOT NULL,
                         Cognome VARCHAR(50) NOT NULL,
                         Telefono VARCHAR(15) NOT NULL,
                         Email VARCHAR(100) NOT NULL UNIQUE,
                         PuntiFedelta INT DEFAULT 0 -- Punti accumulati per la carta fedeltà
);

-- Tabella Indirizzo
CREATE TABLE Indirizzo (
                           ID INT AUTO_INCREMENT PRIMARY KEY,
                           ClienteID INT NOT NULL,
                           Indirizzo VARCHAR(255) NOT NULL,
                           Città VARCHAR(50) NOT NULL,
                           CAP VARCHAR(10) NOT NULL,
                           Paese VARCHAR(50) NOT NULL,
                           FOREIGN KEY (ClienteID) REFERENCES Cliente(ID) ON DELETE CASCADE
);

-- Tabella Spedizione
CREATE TABLE Spedizione (
                            ID INT AUTO_INCREMENT PRIMARY KEY,
                            CodiceUnivoco VARCHAR(20) NOT NULL UNIQUE,
                            MittenteID INT NOT NULL,
                            DestinatarioID INT NOT NULL,
                            Stato ENUM('in partenza', 'in transito', 'consegnato') NOT NULL DEFAULT 'in partenza',
                            DataSpedizione DATETIME NOT NULL,
                            DataRitiro DATETIME DEFAULT NULL,
                            DataConsegna DATETIME DEFAULT NULL,
                            FOREIGN KEY (MittenteID) REFERENCES Cliente(ID) ON DELETE CASCADE,
                            FOREIGN KEY (DestinatarioID) REFERENCES Cliente(ID) ON DELETE CASCADE,
                            FOREIGN KEY (CorriereID) REFERENCES Corriere(ID) ON DELETE CASCADE
);

-- Tabella Corriere
CREATE TABLE Corriere (
                          ID INT AUTO_INCREMENT PRIMARY KEY,
                          Nome VARCHAR(50) NOT NULL,
                          Telefono VARCHAR(15) NOT NULL,
                          Email VARCHAR(100) NOT NULL UNIQUE,
                          DataAssunzione DATETIME NOT NULL
);

-- Tabella Servizi
CREATE TABLE Servizi (
                         ID INT AUTO_INCREMENT PRIMARY KEY,
                         Nome VARCHAR(100) NOT NULL,
                         Descrizione TEXT NOT NULL,
                         CreatoDa INT NOT NULL,
                         FOREIGN KEY (CreatoDa) REFERENCES Utente(ID) ON DELETE CASCADE
);

-- Tabella RichiestaInformazioni
CREATE TABLE RichiestaInformazioni (
                                       ID INT AUTO_INCREMENT PRIMARY KEY,
                                       Nome VARCHAR(50) NOT NULL,
                                       Email VARCHAR(100) NOT NULL,
                                       Messaggio TEXT NOT NULL,
                                       DataRichiesta DATETIME DEFAULT CURRENT_TIMESTAMP,
                                       UtenteID INT NOT NULL,
                                       ServizioID INT NOT NULL,
                                       FOREIGN KEY (UtenteID) REFERENCES Utente(ID) ON DELETE CASCADE,
                                       FOREIGN KEY (ServizioID) REFERENCES Servizi(ID) ON DELETE CASCADE
);

-- Tabella Feedback
CREATE TABLE Feedback (
                          ID INT AUTO_INCREMENT PRIMARY KEY,
                          SpedizioneID INT NOT NULL,
                          Valutazione INT CHECK (Valutazione >= 1 AND Valutazione <= 5),
                          Commento TEXT,
                          DataFeedback DATETIME DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (SpedizioneID) REFERENCES Spedizione(ID) ON DELETE CASCADE,
                          ServizioID INT NOT NULL,
                          FOREIGN KEY (ServizioID) REFERENCES Servizi(ID) ON DELETE CASCADE
);

INSERT INTO Cliente (Nome, Cognome, Telefono, Email, PuntiFedelta) VALUES
                                                                       ('Mario', 'Rossi', '1234567890', 'mario.rossi@example.com', 10),
                                                                       ('Giulia', 'Bianchi', '0987654321', 'giulia.bianchi@example.com', 20),
                                                                       ('Luca', 'Verdi', '1122334455', 'luca.verdi@example.com', 5),
                                                                       ('Sara', 'Neri', '2233445566', 'sara.neri@example.com', 15),
                                                                       ('Francesco', 'Gialli', '3344556677', 'francesco.gialli@example.com', 0);

ALTER TABLE RichiestaInformazioni
    MODIFY UtenteID INT NULL; -- Rendi UtenteID nullable

ALTER TABLE RichiestaInformazioni

    MODIFY ServizioID INT NULL; -- Rendi ServizioID nullable