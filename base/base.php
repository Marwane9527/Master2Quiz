<?php
$host = 'dpg-cs744tggph6c73fdgnb0-a';
$dbname = 'Master2quiz';
$username = 'quiz_a7sk_user';
$password = '2e9oK7D491cQOly9HyyRuIGVdtxvWd6T';
$port = '5432'; // Ajout d'un point-virgule manquant

try {
    // Correction du DSN pour PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;charset=utf8"; // Utiliser 'pgsql' au lieu de 'mysql'

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    // Établir la connexion à la base de données
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>