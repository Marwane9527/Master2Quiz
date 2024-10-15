<?php
session_start();

include_once '../base/base.php';

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['id'] !== 4) {
    // Si l'utilisateur n'est pas connecté ou s'il n'a pas l'ID 4, on le redirige vers la page de connexion
    header('Location: connexion/connexion.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admininstrateur | Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            width: 23rem;
        }
    </style>
</head>

<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="panel.php">Panel de l'administrateur</a>
            <a href="../index.php">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    Accéder au site client
                </button>
            </a>
        </div>
    </nav>

    <br>

    <div class="card-container">
        <div class="card text-center mb-3">
            <div class="card-body">
                <h5 class="card-title">Utilisateurs</h5>
                <p class="card-text">Gestion des utilisateurs</p>
                <a href="user/user.php" class="btn btn-primary">Consulter</a>
            </div>
        </div>
        <div class="card text-center mb-3">
            <div class="card-body">
                <h5 class="card-title">Quizz</h5>
                <p class="card-text">Liste de tous les quizz</p>
                <a href="quiz/quiz.php" class="btn btn-primary">Consulter</a>
            </div>
        </div>
        <div class="card text-center mb-3">
            <div class="card-body">
                <h5 class="card-title">Compte</h5>
                <p class="card-text">Consultez les informations de votre compte </p>
                <a href="profil/profil.php" class="btn btn-primary">Consulter</a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>