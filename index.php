<?php
session_start();

include_once 'base/base.php';


if (isset($_SESSION['id'])) {
    // Requête pour obtenir les données de l'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['id']]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);


}

?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master2Quiz | Accueil</title>
    <!-- Lien vers Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container">
            <a class="navbar-brand" href="index.php">Master2Quiz</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="quiz/allquiz.php">Quiz</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto ">
                    <?php if (isset($_SESSION['id'])): ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle " data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profil/profil.php">Profil</a></li>
                                <?php if ($user_data['id'] == 1): ?>
                                    <li>
                                        <a class="dropdown-item" href="admin/panel.php">Panel</a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="connexion/deconnexionv2.php">Se déconnecter</a></li>
                            </ul>
                        </div>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion/connexion.php">Connexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Section principale -->
    <header class="bg-danger text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Bienvenue sur Master2Quiz ! ESSAI</h1>
            <p class="lead">Testez vos connaissances avec nos quiz sur divers sujets.</p>
            <p class="lead">Ou alors créez vos propres quiz !</p>
            <a href="quiz/creer.php" class="btn btn-lg btn-dark">Créer un Quiz</a>
        </div>
    </header>

    <!-- Liste des quiz -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Nos Quiz Populaires</h2>
            <div class="row">
                <!-- Quiz 1 -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://cdn.pixabay.com/photo/2023/06/05/07/19/einstein-8041625_1280.png"
                            class="card-img-top" alt="Quiz 1">
                        <div class="card-body">
                            <h5 class="card-title">Quiz sur la Culture Générale</h5>
                            <p class="card-text">Testez vos connaissances générales avec ce quiz de 10 questions.</p>
                            <a href="#" class="btn btn-danger">Commencer</a>
                        </div>
                    </div>
                </div>
                <!-- Quiz 2 -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://cdn.pixabay.com/photo/2013/07/12/14/07/basketball-147794_1280.png"
                            class="card-img-top" alt="Quiz 2">
                        <div class="card-body">
                            <h5 class="card-title">Quiz sur le Sport</h5>
                            <p class="card-text">Prouvez que vous êtes un sportif professionel avec ce quiz.</p>
                            <a href="#" class="btn btn-danger">Commencer</a>
                        </div>
                    </div>
                </div>
                <!-- Quiz 3 -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="https://cdn.pixabay.com/photo/2024/08/24/00/57/joconda-8993082_1280.jpg"
                            class="card-img-top" alt="Quiz 3">
                        <div class="card-body">
                            <h5 class="card-title">Quiz sur l'Histoire</h5>
                            <p class="card-text">Revisitez les grands événements de l'histoire avec ce quiz.</p>
                            <a href="#" class="btn btn-danger">Commencer</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class=" text-center py-4">
        <div class="container">
            <p class="mb-0">© 2024 Master2Quiz. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Lien vers Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>