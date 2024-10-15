<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../base/base.php";

if (isset($_SESSION['id'])) {
    // Requête pour obtenir les données de l'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = :id");
    $stmt->execute(['id' => $_SESSION['id']]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quiz_id = isset($_POST['quiz_id']) ? (int) $_POST['quiz_id'] : 0;
    $user_id = $_SESSION['id']; // Assurez-vous que l'utilisateur est connecté

    if ($quiz_id <= 0 || !isset($user_id)) {
        echo "Quiz ou utilisateur invalide.";
        exit;
    }

    try {
        // Commence une transaction
        $pdo->beginTransaction();

        // Crée une entrée dans la table des résultats
        $stmt_result = $pdo->prepare("INSERT INTO results (user_id, quiz_id, score) VALUES (:user_id, :quiz_id, 0)");
        $stmt_result->execute(['user_id' => $user_id, 'quiz_id' => $quiz_id]);
        $result_id = $pdo->lastInsertId();

        // Initialiser le score
        $score = 0;

        // Parcourir les réponses de l'utilisateur
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $question_id = (int) str_replace('question_', '', $key);
                $answer_id = (int) $value;

                // Vérifie si la réponse est correcte
                $stmt_correct_answer = $pdo->prepare("
                    SELECT is_correct FROM answers WHERE id = :answer_id AND question_id = :question_id
                ");
                $stmt_correct_answer->execute(['answer_id' => $answer_id, 'question_id' => $question_id]);
                $is_correct = $stmt_correct_answer->fetchColumn();

                // Enregistre la réponse dans la table user_answers
                $stmt_user_answer = $pdo->prepare("
                    INSERT INTO user_answers (result_id, question_id, answer_id) 
                    VALUES (:result_id, :question_id, :answer_id)
                ");
                $stmt_user_answer->execute([
                    'result_id' => $result_id,
                    'question_id' => $question_id,
                    'answer_id' => $answer_id
                ]);

                // Augmenter le score si la réponse est correcte
                if ($is_correct) {
                    $score++;
                }
            }
        }

        // Mise à jour du score dans la table des résultats
        $stmt_update_score = $pdo->prepare("UPDATE results SET score = :score WHERE id = :result_id");
        $stmt_update_score->execute(['score' => $score, 'result_id' => $result_id]);

        // Confirme la transaction
        $pdo->commit();

        // Redirection vers la page des résultats ou afficher un message de succès
        header("Location: result.php?id=$result_id");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erreur : " . $e->getMessage();
        exit;
    }
}
?>