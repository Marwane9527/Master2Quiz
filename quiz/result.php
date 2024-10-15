<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../base/base.php";

// Vérifier si un identifiant de résultat est passé via l'URL
$result_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($result_id <= 0) {
    echo "Résultat non valide.";
    exit;
}

if (isset($_SESSION['id'])) {
    // Requête pour obtenir les données de l'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['id']]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupérer les informations du résultat
try {
    // Récupérer le score et les détails du quiz
    $stmt_result = $pdo->prepare("
        SELECT r.score, q.title, u.username, r.completed_at 
        FROM results r
        JOIN quizzes q ON r.quiz_id = q.id
        JOIN users u ON r.user_id = u.id
        WHERE r.id = :result_id
    ");
    $stmt_result->execute(['result_id' => $result_id]);
    $result = $stmt_result->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        echo "Résultat introuvable.";
        exit;
    }

    // Récupérer les réponses de l'utilisateur pour ce résultat
    $stmt_user_answers = $pdo->prepare("
        SELECT q.question_text, ua.answer_id, a.answer_text, a.is_correct
        FROM user_answers ua
        JOIN questions q ON ua.question_id = q.id
        JOIN answers a ON ua.answer_id = a.id
        WHERE ua.result_id = :result_id
    ");
    $stmt_user_answers->execute(['result_id' => $result_id]);
    $user_answers = $stmt_user_answers->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master2Quiz | Résultat du Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                                <li><a class="dropdown-item" href="../profil/profil.php">Profil</a></li>
                                <?php if ($user_data['id'] == 4): ?>
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center">Résultat du Quiz : <?= htmlspecialchars($result['title']); ?></h1>

                <!-- Condition pour afficher le message en fonction du score -->
                <div class="alert <?= $result['score'] >= 3 ? 'alert-success' : 'alert-warning'; ?> text-center">
                    <?php if ($result['score'] >= 3): ?>
                        <p>Félicitations <?= htmlspecialchars($result['username']); ?> ! Vous avez réussi le quiz.</p>
                    <?php else: ?>
                        <p>Dommage <?= htmlspecialchars($result['username']); ?>. Vous ferez mieux la prochaine fois !</p>
                    <?php endif; ?>
                </div>

                <p class="text-center fs-4">Votre score est de : <strong><?= $result['score']; ?></strong></p>

                <div class="mt-4">
                    <h3 class="mb-4">Détails des réponses :</h3>
                    <?php foreach ($user_answers as $answer): ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($answer['question_text']); ?></h5>
                                <p>Votre réponse : <strong><?= htmlspecialchars($answer['answer_text']); ?></strong></p>
                                <?php if ($answer['is_correct']): ?>
                                    <p class="text-success"><strong>Réponse correcte !</strong></p>
                                <?php else: ?>
                                    <p class="text-danger"><strong>Mauvaise réponse.</strong></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="allquiz.php" class="btn btn-primary">Retourner à la liste des quiz</a>
                </div>
            </div>
        </div>
    </div>


    <footer class="text-center py-4">
        <div class="container">
            <p class="mb-0">© 2024 Master2Quiz. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>