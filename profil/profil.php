<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../base/base.php";

if (!isset($_SESSION['id'])) {
    header('Location: ../connexion/connexion.php');
    exit();
}

if (isset($_SESSION['id'])) {
    // Requête pour obtenir les données de l'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['id']]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);


}

$sql = "SELECT id, username, email, is_verified, created_at FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_SESSION['id']]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    header('location: ../connexion/connexion.php');
    exit();
}

$user_id = $_SESSION['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $user_id]);

    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erreur lors de la récupération des quiz : " . $e->getMessage());
    $quizzes = [];
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master2Quiz | Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            width: 220px;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 1.1rem;
            color: #333;
            display: block;
        }

        .sidebar a:hover {
            background-color: #ddd;
            color: #000;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Master2Quiz</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../quiz/allquiz.php">Quiz</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto ">
                    <?php if (isset($_SESSION['id'])): ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                                </svg>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="profil.php">Profil</a></li>
                                <?php if ($user_data['id'] == 1): ?>
                                    <li>
                                        <a class="dropdown-item" href="../admin/panel.php">Panel</a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../connexion/deconnexionv2.php">Se déconnecter</a></li>
                            </ul>
                        </div>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../connexion/connexion.php">Connexion</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Sidebar -->
    <!-- Sidebar -->
    <div class="container mt-5">
        <div class="row">
            <!-- Sidebar avec navigation -->
            <div class="col-md-4 mb-4">
                <div id="list-example" class="list-group shadow-sm">
                    <a class="list-group-item list-group-item-action" href="#list-item-1">Mon profil</a>
                    <a class="list-group-item list-group-item-action" href="#list-item-2">Mes quizz</a>
                    <a class="list-group-item list-group-item-action" href="#list-item-3">Sécurité</a>
                    <a class="list-group-item list-group-item-action" href="#list-item-4">Communautés</a>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="col-md-8">
                <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true"
                    class="scrollspy-example" tabindex="0">

                    <!-- Section Profil -->
                    <h4 id="list-item-1">Profil de <?= htmlspecialchars($utilisateur['username']); ?></h4>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Informations personnelles</h5>
                            <p class="card-text"><strong>Nom :</strong>
                                <?= htmlspecialchars($utilisateur['username']); ?></p>
                            <p class="card-text"><strong>Email :</strong>
                                <?= htmlspecialchars($utilisateur['email']); ?></p>
                            <p class="card-text"><strong>Statut de l'email :</strong>
                                <?= $utilisateur['is_verified'] ? '<span class="badge bg-success">Vérifié</span>' : '<span class="badge bg-danger">Non vérifié</span>'; ?>
                            </p>
                            <p class="card-text"><strong>Date d'inscription :</strong>
                                <?= htmlspecialchars($utilisateur['created_at']); ?></p>
                            <a href="modif.php" class="btn btn-warning">Modifier le profil</a>
                        </div>
                    </div>

                    <!-- Section Mes quiz -->
                    <h4 id="list-item-2">Mes quiz</h4>
                    <?php if (count($quizzes) > 0): ?>
                        <div class="row">
                            <?php foreach ($quizzes as $quiz): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= htmlspecialchars($quiz['title']); ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($quiz['description']); ?></p>
                                            <p class="card-text"><small class="text-muted">Créé le
                                                    <?= htmlspecialchars($quiz['created_at']); ?></small></p>
                                            <a href="../quiz/jouer.php?id=<?= $quiz['id']; ?>"
                                                class="btn btn-primary w-100">Jouer le Quiz</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">Vous n'avez créé aucun quiz pour le moment.</p>
                    <?php endif; ?>

                    <!-- Section Sécurité -->
                    <h4 id="list-item-3">Sécurité</h4>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <p>Paramètres de sécurité...</p>
                        </div>
                    </div>

                    <!-- Section Communautés -->
                    <h4 id="list-item-4">Communautés</h4>
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <p>Informations sur les communautés...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer class="text-center py-4">
        <div class="container">
            <p class="mb-0">© 2024 MasterQuizz. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>