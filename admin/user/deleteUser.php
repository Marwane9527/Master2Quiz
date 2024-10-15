<?php
session_start();


include_once "../../base/base.php";

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    try {
        $sql = $pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($sql->execute([$user_id])) {
            header("Location: user.php?message=Utilisateur supprimé avec succès !");
            exit;
        } else {
            header("Location: user.php?message=La suppression de l'utilisateur a échoué.");
            exit;
        }
    } catch (PDOException $e) {
        echo "Erreur de connexion : " . $e->getMessage();
    }
} else {
    header("Location: user.php?message=ID d'utilisateur non valide.");
    exit;
}

$pdo = null;
?>