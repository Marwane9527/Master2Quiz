<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../base/base.php";

// Récupérer l'ID du quiz depuis l'URL
$quiz_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($quiz_id <= 0) {
    echo "Quiz non valide.";
    exit;
}

// Récupérer le quiz et ses questions avec leurs réponses
try {
    // Récupérer les informations du quiz
    $stmt_quiz = $pdo->prepare("SELECT * FROM quizzes WHERE id = :quiz_id");
    $stmt_quiz->execute(['quiz_id' => $quiz_id]);
    $quiz = $stmt_quiz->fetch(PDO::FETCH_ASSOC);

    if (!$quiz) {
        echo "Quiz introuvable.";
        exit;
    }

    // Récupérer les questions du quiz avec leurs réponses
    $stmt_questions = $pdo->prepare("
        SELECT q.id as question_id, q.question_text, a.id as answer_id, a.answer_text 
        FROM questions q 
        JOIN answers a ON q.id = a.question_id 
        WHERE q.quiz_id = :quiz_id
    ");
    $stmt_questions->execute(['quiz_id' => $quiz_id]);

    $questions = [];
    while ($row = $stmt_questions->fetch(PDO::FETCH_ASSOC)) {
        $questions[$row['question_id']]['question_text'] = $row['question_text'];
        $questions[$row['question_id']]['answers'][] = [
            'answer_id' => $row['answer_id'],
            'answer_text' => $row['answer_text']
        ];
    }

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
    <title>Master2Quiz | <?= htmlspecialchars($quiz['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .question-container {
            display: none;
        }

        .active {
            display: block;
        }
    </style>
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
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center"><?= htmlspecialchars($quiz['title']); ?></h1>
                <p class="text-center"><?= htmlspecialchars($quiz['description']); ?></p>

                <!-- Affichage du compteur de progression -->
                <div class="text-center mb-4">
                    <span id="progress-counter">Question 1 sur <?= count($questions); ?></span>
                </div>
                <br>

                <form id="quizForm" action="submit.php" method="post">
                    <input type="hidden" name="quiz_id" value="<?= $quiz_id; ?>">

                    <?php $index = 1;
                    foreach ($questions as $question_id => $question): ?>
                        <div class="question-container <?= $index === 1 ? 'active' : ''; ?>"
                            data-question-index="<?= $index; ?>">
                            <h2 class="text-center"><?= htmlspecialchars($question['question_text']); ?></h2>
                            <br>
                            <br>
                            <div class="row justify-content-center">
                                <?php foreach ($question['answers'] as $answer): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card p-3 text-center shadow-sm">
                                            <div class="form-check">
                                                <input class="form-check-input" style="transform: scale(1.5);" type="radio"
                                                    name="question_<?= $question_id; ?>" value="<?= $answer['answer_id']; ?>"
                                                    required>
                                                <label class="form-check-label h5">
                                                    <?= htmlspecialchars($answer['answer_text']); ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php $index++; endforeach; ?>

                    <!-- Boutons de navigation -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" id="prevBtn" class="btn btn-secondary"
                            style="display: none;">Précédent</button>
                        <button type="button" id="nextBtn" class="btn btn-primary">Suivant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <footer class="text-center py-4">
        <div class="container">
            <p class="mb-0">© 2024 Master2Quiz. Tous droits réservés.</p>
        </div>
    </footer>


    <script>
        const totalQuestions = <?= count($questions); ?>;
        let currentQuestion = 1;

        document.getElementById('nextBtn').addEventListener('click', function () {
            showQuestion(currentQuestion + 1);
        });

        document.getElementById('prevBtn').addEventListener('click', function () {
            showQuestion(currentQuestion - 1);
        });

        function showQuestion(questionIndex) {
            const allQuestions = document.querySelectorAll('.question-container');
            if (questionIndex < 1 || questionIndex > totalQuestions) return;

            allQuestions.forEach((q, i) => {
                q.classList.toggle('active', i === questionIndex - 1);
            });

            currentQuestion = questionIndex;
            document.getElementById('progress-counter').textContent = `Question ${currentQuestion} sur ${totalQuestions}`;

            // Gérer l'affichage des boutons "Suivant" et "Précédent"
            document.getElementById('prevBtn').style.display = currentQuestion > 1 ? 'inline-block' : 'none';
            document.getElementById('nextBtn').textContent = currentQuestion === totalQuestions ? 'Soumettre' : 'Suivant';

            if (currentQuestion === totalQuestions) {
                document.getElementById('nextBtn').addEventListener('click', function () {
                    document.getElementById('quizForm').submit();
                });
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>