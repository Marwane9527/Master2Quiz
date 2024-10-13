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

$sql = "SELECT id, username, email FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_SESSION['id']]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    echo "Utilisateur non trouvé.";
    exit();
}

$erreur = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];

    // Validation des champs
    if (empty($nom) || empty($email)) {
        $erreur = "Le nom et l'email sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "L'adresse email n'est pas valide.";
    } else {
        try {
            $sql = "UPDATE users SET username = :username, email = :email";

            $sql .= " WHERE id = :id";
            $stmt = $pdo->prepare($sql);

            $params = [
                'username' => $nom,
                'email' => $email,
                'id' => $_SESSION['id']
            ];


            $stmt->execute($params);

            header("location: profil.php");
        } catch (PDOException $e) {
            $erreur = "Erreur lors de la mise à jour : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="../index.php"></a>
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
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['id'])): ?>
                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
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
                                <?php if ($_SESSION['role'] === 'gestionnaire' || $_SESSION['role'] === 'super-administrateur'): ?>
                                    <li>
                                        <a class="dropdown-item" href="#">Panel</a>
                                    </li>
                                <?php endif; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../connexion/deconnexion.php">Se déconnecter</a></li>
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
        <h2>Modifier votre profil</h2>

        <?php if ($erreur): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($erreur); ?>
            </div>
        <?php endif; ?>

        <?php if ($message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom"
                    value="<?php echo htmlspecialchars($utilisateur['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($utilisateur['email']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>

    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p>&copy; 2024 Fun Fair. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdsl" crossorigin="anonymous"></script>
</body>

</html>