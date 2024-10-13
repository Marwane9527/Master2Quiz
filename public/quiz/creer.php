<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['id'])) {
    // Si l'utilisateur n'est pas connecté, le rediriger vers la page de connexion
    header('Location: ../connexion/connexion.php');
    exit;
}

include_once '../base/base.php';

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Étape 1 : Création du quiz
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Insérer un nouveau quiz créé par un utilisateur



    // Insertion du quiz dans la table "quizzes"
    $sql = "INSERT INTO quizzes (user_id, title, description) VALUES (:user_id, :title, :description)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':user_id' => $_SESSION['id'],   // L'utilisateur connecté (créateur du quiz)
        ':title' => $title,
        ':description' => $description
    ]);

    // Récupération de l'ID du quiz créé
    $quiz_id = $pdo->lastInsertId();

    // Étape 2 : Ajout des questions et réponses
    foreach ($_POST['questions'] as $index => $question_text) {
        // Insertion de chaque question dans la table "questions"
        $stmt = $pdo->prepare('INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)');
        $stmt->execute([$quiz_id, $question_text]);

        // Récupération de l'ID de la question créée
        $question_id = $pdo->lastInsertId();

        // Ajout des réponses associées à cette question
        foreach ($_POST['answers'][$index] as $answer_index => $answer_text) {
            // Vérification si c'est la réponse correcte
            $is_correct = $_POST['correct_answer'][$index] == $answer_index ? 1 : 0;

            // Insertion des réponses dans la table "answers"
            $stmt = $pdo->prepare('INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)');
            $stmt->execute([$question_id, $answer_text, $is_correct]);
        }
    }

    header("location:../index.php");
}
?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master2Quiz | Quiz</title>
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
                                <li><a class="dropdown-item" href="../profil/profil.php">Profil</a></li>
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


    <div class="container mt-5">
        <div class="card shadow-lg p-4 rounded">
            <h1 class="text-center mb-4">Créer un nouveau quiz</h1>
            <form method="POST">
                <!-- Étape 1 : Informations sur le quiz -->
                <div class="mb-3">
                    <label for="title" class="form-label">Titre du quiz</label>
                    <input type="text" class="form-control" id="title" name="title"
                        placeholder="Entrez le titre du quiz" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                        placeholder="Description du quiz" required></textarea>
                </div>

                <!-- Étape 2 : Ajouter des questions et réponses -->
                <div id="questions-container">
                    <h3 class="mb-4">Questions</h3>

                    <!-- Première question (par défaut) -->
                    <div class="question-item mb-4">
                        <div class="card p-3 mb-3 shadow-sm">
                            <div class="mb-3">
                                <label for="question_text" class="form-label">Question 1</label>
                                <input type="text" class="form-control" name="questions[]"
                                    placeholder="Entrez la question" required>
                            </div>

                            <!-- Réponses pour la question -->
                            <div class="mb-3">
                                <label class="form-label">Réponses</label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[0][]" placeholder="Réponse 1"
                                        required>
                                    <input type="radio" name="correct_answer[0]" value="0" class="form-check-input mx-2"
                                        required> Correct
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[0][]" placeholder="Réponse 2"
                                        required>
                                    <input type="radio" name="correct_answer[0]" value="1" class="form-check-input mx-2"
                                        required> Correct
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[0][]" placeholder="Réponse 3"
                                        required>
                                    <input type="radio" name="correct_answer[0]" value="2" class="form-check-input mx-2"
                                        required> Correct
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[0][]" placeholder="Réponse 4"
                                        required>
                                    <input type="radio" name="correct_answer[0]" value="3" class="form-check-input mx-2"
                                        required> Correct
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-question" class="btn btn-primary mb-4">Ajouter une
                    question</button>
                <button type="submit" class="btn btn-danger">Créer le quiz</button>
            </form>
        </div>
    </div>



    <footer class=" text-center py-4">
        <div class="container">
            <p class="mb-0">© 2024 Master2Quiz. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Script pour ajouter dynamiquement des questions et réponses -->
    <script>
        let questionCount = 1;

        document.getElementById('add-question').addEventListener('click', function () {
            const questionsContainer = document.getElementById('questions-container');
            const questionTemplate = `
                <div class="question-item mb-4">
                        <div class="card p-3 mb-3 shadow-sm">
                            <div class="mb-3">
                                <label for="question_text" class="form-label">Question ${questionCount + 1}</label>
                                <input type="text" class="form-control" name="questions[]"
                                    placeholder="Entrez la question" required>
                            </div>

                            <!-- Réponses pour la question -->
                            <div class="mb-3">
                                <label class="form-label">Réponses</label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[${questionCount}][]" placeholder="Réponse 1"
                                        required>
                                    <input type="radio" name="correct_answer[${questionCount}]" value="0" class="form-check-input mx-2"
                                        required> Correct
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[${questionCount}][]" placeholder="Réponse 2"
                                        required>
                                    <input type="radio" name="correct_answer[${questionCount}]" value="1" class="form-check-input mx-2"
                                        required> Correct
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[${questionCount}][]" placeholder="Réponse 3"
                                        required>
                                    <input type="radio" name="correct_answer[${questionCount}]" value="2" class="form-check-input mx-2"
                                        required> Correct
                                </div>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="answers[${questionCount}][]" placeholder="Réponse 4"
                                        required>
                                    <input type="radio" name="correct_answer[${questionCount}]" value="3" class="form-check-input mx-2"
                                        required> Correct
                                </div>
                            </div>
                        </div>
                    </div>
            `;

            questionsContainer.insertAdjacentHTML('beforeend', questionTemplate);
            questionCount++;
        });
    </script>

    <!-- Lien vers Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>