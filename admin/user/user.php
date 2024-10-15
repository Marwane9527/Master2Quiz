<?php
session_start();

if (!isset($_SESSION['user_data']) || $_SESSION['user_data']['id'] !== 4) {
    // Si l'utilisateur n'est pas connecté ou s'il n'a pas l'ID 4, on le redirige vers la page de connexion
    header('Location: ../../connexion/connexion.php');
    exit();
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


?>

<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../panel.php">Administrateur | Utilisateurs</a>
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
                        <li><a class="nav-link active" aria-current="page" href="../forum/forum.php">Forum</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page" href="../concours/concours.php">Concours</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page" href="../manege/manege.php">Attractions</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page"
                                href="../affluance/affluence.php">Affluence</a>
                        </li>
                        <li><a class="nav-link active" aria-current="page" href="../admin/infoadmin.php">Informations
                                sur votre compte</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Pseudonyme</th>
                <th scope="col">Email</th>
                <th scope="col">Suppression</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                include_once "../../base/base.php";
                $sql = "SELECT id, username, email FROM users";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll();
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($row['id']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($row['username']) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars($row['email']) ?>
                        </td>
                        <td id="deleteUser"><a href="deleteUser.php?id=<?= $row['id'] ?>"><button style="background-color: red;"
                                    id="supprimer" type="button" class="btn btn-secondary btn-sm">Supprimer</button></a></td>
                    </tr>

                    <?php
                    }

                } else {
                    echo "<tr><td colspan='5' class='text-center'>Aucun utilisateur présent !</td></tr>";

                }
                ?>
            </tr>
        </tbody>
    </table>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>