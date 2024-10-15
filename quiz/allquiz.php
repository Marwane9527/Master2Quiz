<?php
session_start();
include_once "../base/base.php";

// Vérifier s'il y a une recherche effectuée
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Préparer la requête SQL avec ou sans recherche
if (!empty($search)) {
    // Requête avec un filtre de recherche sur le titre du quiz et jointure avec la table users
    $stmt = $pdo->prepare("
        SELECT quizzes.*, users.username 
        FROM quizzes 
        JOIN users ON quizzes.user_id = users.id 
        WHERE quizzes.title LIKE :search 
        ORDER BY quizzes.created_at DESC
    ");
    $stmt->execute(['search' => '%' . $search . '%']);
} else {
    // Requête sans filtre de recherche, affichant tous les quiz avec jointure
    $stmt = $pdo->query("
        SELECT quizzes.*, users.username 
        FROM quizzes 
        JOIN users ON quizzes.user_id = users.id 
        ORDER BY quizzes.created_at DESC
    ");
}

// Récupérer les résultats des quiz
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master2Quiz | Liste des Quiz</title>
    <!-- Lien vers Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
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
                        <a class="nav-link" href="allquiz.php">Quiz</a>
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



    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Liste des Quiz Disponibles</h1>

            <!-- Formulaire de recherche -->
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Rechercher un quiz"
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
        </div>

        <?php if (count($quizzes) > 0): ?>
            <div class="row">
                <?php foreach ($quizzes as $quiz): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($quiz['title']); ?></h5>
                                <p class="card-text"><?= htmlspecialchars($quiz['description']); ?></p>
                                <p class="card-text"><small class="text-muted">Créé par :
                                        <?= htmlspecialchars($quiz['username']); ?></small></p>
                                <!-- Lien dynamique vers la page du quiz -->
                                <a href="jouer.php?id=<?= $quiz['id']; ?>" class="btn btn-danger">Jouer le Quiz</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <p>Aucun quiz disponible pour le moment.</p>
        <?php endif; ?>
    </div>

    <footer class=" text-center py-4">
        <div class="container">
            <p class="mb-0">© 2024 Master2Quiz. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Lien vers Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>