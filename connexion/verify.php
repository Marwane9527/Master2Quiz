<?php

include_once '../base/base.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $verification_code = $_POST['code'];

    // Vérification que le code est bien un nombre de 6 chiffres
    if (strlen($verification_code) === 6 && ctype_digit($verification_code)) {
        try {
            // Requête pour trouver l'utilisateur avec le code de vérification
            $sql = "SELECT * FROM users WHERE verification_code = :verification_code";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':verification_code' => $verification_code]);

            $user = $stmt->fetch();

            if ($user) {
                // Mise à jour pour marquer l'utilisateur comme vérifié
                $sql = "UPDATE users SET is_verified = TRUE, verification_code = NULL WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $user['id']]);

                $message = "<div class='alert alert-success'>Votre adresse email a été vérifiée avec succès ! Vous pouvez maintenant vous connecter en cliquant <a href='connexion.php'>ici</a>.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Code de vérification invalide. Veuillez réessayer.</div>";
            }
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Une erreur est survenue lors de la vérification : " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Le code de vérification est invalide. Veuillez entrer un code à 6 chiffres.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'Email</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="mt-5">Vérification de l'Email</h2>

                <?php if (!empty($message)) echo $message; ?>

                <form action="verify.php" method="post" class="mt-3">
                    <div class="mb-3">
                        <label for="code" class="form-label">Entrez le code de vérification que vous avez reçu par email :</label>
                        <input type="text" id="code" name="code" class="form-control" required pattern="\d{6}" title="Le code doit être composé de 6 chiffres.">
                    </div>
                    <button type="submit" class="btn btn-primary">Vérifier</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
