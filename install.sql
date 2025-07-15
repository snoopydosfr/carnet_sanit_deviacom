-- Base de données : carnet_sanitaire
CREATE DATABASE IF NOT EXISTS carnet_sanitaire;
USE carnet_sanitaire;

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'technicien') DEFAULT 'technicien',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Informations établissement
CREATE TABLE etablissement (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255),
    statut VARCHAR(255),
    finess VARCHAR(50),
    adresse TEXT,
    commune VARCHAR(100),
    telephone VARCHAR(20),
    directeur VARCHAR(100),
    capacite_accueil INT,
    nb_batiments INT,
    date_fermeture_debut DATE,
    date_fermeture_fin DATE
);

-- Intervenants internes
CREATE TABLE intervenants_internes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    fonction VARCHAR(100),
    telephone VARCHAR(20)
);

-- Intervenants externes
CREATE TABLE intervenants_externes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    societe VARCHAR(100),
    domaine VARCHAR(100),
    telephone VARCHAR(20),
    contrat_expiration DATE
);

-- Fiche installation
CREATE TABLE fiche_installation (
    id INT PRIMARY KEY AUTO_INCREMENT,
    origine_eau VARCHAR(50),
    diagnostic_date DATE,
    reseau_eau_froide TEXT,
    reseau_eau_chaude TEXT,
    traitement_eau TEXT
);

-- Maintenance hebdomadaire
CREATE TABLE maintenance_hebdo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    semaine INT,
    annee INT,
    point_usage VARCHAR(100),
    temperature DECIMAL(5,2),
    chasse_ballon BOOLEAN,
    date_operation DATE,
    utilisateur_id INT,
    FOREIGN KEY (utilisateur_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS surveillance_temperature (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mois VARCHAR(20),
    annee INT,
    point_surveillance VARCHAR(100),
    jan DECIMAL(5,2),
    fev DECIMAL(5,2),
    mar DECIMAL(5,2),
    avr DECIMAL(5,2),
    mai DECIMAL(5,2),
    jun DECIMAL(5,2),
    jul DECIMAL(5,2),
    aou DECIMAL(5,2),
    sep DECIMAL(5,2),
    `oct` DECIMAL(5,2),  
    nov DECIMAL(5,2), 
    `dec` DECIMAL(5,2)    
);

-- Analyses légionelles
CREATE TABLE analyse_legionelle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    point_prelevement VARCHAR(100),
    date DATE,
    temperature DECIMAL(5,2),
    mode_prelevement VARCHAR(50),
    resultat INT,
    action TEXT
);