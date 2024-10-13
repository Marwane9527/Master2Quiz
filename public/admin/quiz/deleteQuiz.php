<?php
session_start();


include_once "../../base/base.php";

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    try {
        $sql = $pdo->prepare("DELETE FROM quizzes WHERE id = ?");
        if ($sql->execute([$user_id])) {
            header("Location: quiz.php?message=Quiz supprimé avec succès !");
            exit;
        } else {
            header("Location: quiz.php?message=La suppression du Quiz a échoué.");
            exit;
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
} else {
    header("Location: quiz.php?message=ID d'utilisateur non valide.");
    exit;
}

$pdo = null;
?>