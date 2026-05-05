-- ==========================================================
-- DATABASE: Gestione Palestra (Fitness Center)
-- ==========================================================

-- 1. CREAZIONE TABELLE

-- Tabella Istruttori
CREATE TABLE Istruttori (
    id_istruttore INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    specializzazione VARCHAR(100),
    certificazione VARCHAR(50)
);

-- Tabella Corsi
CREATE TABLE Corsi (
    id_corso INT PRIMARY KEY AUTO_INCREMENT,
    nome_corso VARCHAR(100) NOT NULL,
    livello_difficolta ENUM('Principiante', 'Intermedio', 'Avanzato'),
    durata_minuti INT DEFAULT 60,
    id_istruttore INT,
    FOREIGN KEY (id_istruttore) REFERENCES Istruttori(id_istruttore) ON DELETE SET NULL
);

-- Tabella Membri
CREATE TABLE Membri (
    id_membro INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    data_nascita DATE,
    tipo_abbonamento VARCHAR(50), -- Es: Mensile, Trimestrale, Annuale
    stato_pagamento BOOLEAN DEFAULT TRUE
);

-- Tabella Iscrizioni_Corsi 
CREATE TABLE Iscrizioni_Corsi (
    id_iscrizione INT PRIMARY KEY AUTO_INCREMENT,
    id_corso INT,
    id_membro INT,
    data_iscrizione DATE NOT NULL,
    orario_preferito TIME,
    FOREIGN KEY (id_corso) REFERENCES Corsi(id_corso) ON DELETE CASCADE,
    FOREIGN KEY (id_membro) REFERENCES Membri(id_membro) ON DELETE CASCADE
);

-- ==========================================================
-- 2. INSERIMENTO TUPLE (5 per tabella)
-- ==========================================================

-- Inserimento Istruttori
INSERT INTO Istruttori (nome, cognome, specializzazione, certificazione) VALUES
('Alessandro', 'Ferri', 'Bodybuilding', 'IFBB Pro'),
('Sara', 'Lombardi', 'Yoga e Pilates', 'RYT 500'),
('Roberto', 'Santi', 'Crossfit', 'L-2 Trainer'),
('Valentina', 'Rinaldi', 'Zumba e Cardio', 'ZIN Official'),
('Fabio', 'Mancini', 'Nuoto e Triathlon', 'FIN Secondo Livello');

-- Inserimento Corsi
INSERT INTO Corsi (nome_corso, livello_difficolta, durata_minuti, id_istruttore) VALUES
('Power Lifting', 'Avanzato', 90, 1),
('Hatha Yoga', 'Principiante', 60, 2),
('WOD Intensity', 'Avanzato', 45, 3),
('Zumba Dance', 'Intermedio', 50, 4),
('Corso Nuoto Adulti', 'Intermedio', 60, 5);

-- Inserimento Membri
INSERT INTO Membri (nome, cognome, data_nascita, tipo_abbonamento, stato_pagamento) VALUES
('Giorgio', 'Moretti', '1995-04-12', 'Annuale', TRUE),
('Alice', 'Donati', '1990-08-25', 'Mensile', TRUE),
('Stefano', 'Ricci', '1988-12-02', 'Trimestrale', FALSE),
('Lucia', 'Parodi', '2001-01-30', 'Mensile', TRUE),
('Davide', 'Galli', '1993-06-15', 'Annuale', TRUE);

-- Inserimento Iscrizioni_Corsi
INSERT INTO Iscrizioni_Corsi (id_corso, id_membro, data_iscrizione, orario_preferito) VALUES
(1, 1, '2024-01-10', '18:30:00'),
(2, 2, '2024-01-11', '10:00:00'),
(3, 3, '2024-01-12', '19:00:00'),
(4, 4, '2024-01-15', '17:30:00'),
(5, 5, '2024-01-20', '08:00:00'),
-- Ulteriori 5 tuple per la tabella di relazione
(1, 5, '2024-01-21', '18:30:00'),
(2, 4, '2024-01-22', '09:00:00'),
(3, 1, '2024-01-23', '19:00:00'),
(5, 2, '2024-01-24', '07:30:00'),
(4, 3, '2024-01-25', '17:30:00');