<?php
session_start();


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../../base/base.php';
$sql = "SELECT username, email, created_at, is_verified FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $_SESSION['id']]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur | Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../panel.php">Administrateur | Profil</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item"><a class="nav-link active" aria-current="page"
                                href="../home.php">Accueil</a></li>
                        <li><a class="nav-link active" aria-current="page" href="user.php">Utilisateurs</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page" href="../captcha/captcha.php">Captcha</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page"
                                href="../newsletter/newsletter.php">Newsletter</a></li>
                        <li><a class="nav-link active" aria-current="page" href="../avis/avis.php">Avis</a>
                        <li><a class="nav-link active" aria-current="page"
                                href="../billeterie/billet.php">Billeterie</a>
                        <li><a class="nav-link active" aria-current="page" href="../forum/forum.php">Forum</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page" href="../concours/concours.php">Concours</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page" href="../manege/manege.php">Attractions</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page"
                                href="../affluance/affluence.php">Affluence</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page" href="signature.php">Signature</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h2 class="card-title mb-0">Profil de <?php echo htmlspecialchars($utilisateur['username']); ?>
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <h5 class="mb-4">Informations personnelles</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Nom :</strong>
                                <span><?php echo htmlspecialchars($utilisateur['username']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Email :</strong>
                                <span><?php echo htmlspecialchars($utilisateur['email']); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Statut de l'email :</strong>
                                <span
                                    class="<?php echo $utilisateur['is_verified'] ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $utilisateur['is_verified'] ? 'Vérifié' : 'Non vérifié'; ?>
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>Date d'inscription :</strong>
                                <span><?php echo htmlspecialchars($utilisateur['created_at']); ?></span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-center py-3">
                        <a href="modif.php" class="btn btn-warning px-4">Modifier le profil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>