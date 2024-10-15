<?php
$host = 'dpg-cs744tggph6c73fdgnb0-a.frankfurt-postgres.render.com';
$dbname = 'quiz_a7sk';
$username = 'quiz_a7sk_user';
$password = '2e9oK7D491cQOly9HyyRuIGVdtxvWd6T';
$port = '5432'; // Port pour PostgreSQL

try {
    // Correction du DSN pour PostgreSQL sans charset
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname"; // Enlever charset

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Gérer les erreurs
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut
        PDO::ATTR_EMULATE_PREPARES => false, // Émuler les préparations pour éviter les attaques par injection SQL
    ];

    // Établir la connexion à la base de données
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage(); // Afficher le message d'erreur
}
?>