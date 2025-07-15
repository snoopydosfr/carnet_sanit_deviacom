<?php
try {
    $host = 'localhost';
    $dbname = 'carnet_sanitaire';
    $username = 'root'; // Mettez votre utilisateur MySQL
    $password = '';     // Mettez votre mot de passe (si nécessaire)

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}