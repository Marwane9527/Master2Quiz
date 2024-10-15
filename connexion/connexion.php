<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "../base/base.php";

if (isset($_POST['connexion'])) {
    if (isset($_POST['email']) && isset($_POST['mot_de_passe'])) {

        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        try {
            // Préparation de la requête pour récupérer l'utilisateur via son email
            $stmt = $pdo->prepare("SELECT id, email, password, username, verification_code FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);

            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user_data) {
                // Vérifier si l'utilisateur a déjà vérifié son email
                if (!empty($user_data['verification_code'])) {
                    $erreur = "Veuillez vérifier votre adresse email avant de vous connecter.";
                } else {
                    // Vérification du mot de passe
                    if (password_verify($mot_de_passe, $user_data['password'])) {
                        // Mot de passe correct, on initialise les variables de session
                        $_SESSION['id'] = $user_data['id'];
                        $_SESSION['nom'] = $user_data['username'];

                        // Redirection basée sur l'ID de l'utilisateur
                        if ($user_data['id'] == 1) {
                            // Rediriger l'utilisateur avec l'ID 2 vers la page admin
                            header('location: ../admin/panel.php');
                        } else {
                            // Redirection vers la page d'accueil pour les autres utilisateurs
                            header('location: ../index.php');
                        }
                        exit;
                    } else {
                        $erreur = "Identifiants incorrects !";
                    }
                }
            } else {
                $erreur = "Identifiants incorrects !";
            }
        } catch (PDOException $e) {
            // Ne pas afficher directement les erreurs SQL en production
            error_log("Erreur de requête SQL : " . $e->getMessage());
            $erreur = "Une erreur est survenue. Veuillez réessayer.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MasterQuizz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .login-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
            border-radius: .375rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="card login-card">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Connexion</h5>
                <?php if (isset($erreur)): ?>
                    <div class="alert alert-danger"><?= $erreur; ?></div>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="nom@exemple.com"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="mot_de_passe" id="password"
                            placeholder="Mot de passe" required>
                    </div>
                    <button type="submit" name="connexion" class="btn btn-primary w-100">Se connecter</button>
                </form>
                <div class="text-center mt-3">
                    <a href="#">Mot de passe oublié ?</a>
                </div>
                <div class="text-center mt-3">
                    <a href="inscription.php">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>