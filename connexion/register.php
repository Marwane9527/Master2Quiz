<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../sendemail/phpmailer/src/Exception.php';
require '../sendemail/phpmailer/src/PHPMailer.php';
require '../sendemail/phpmailer/src/SMTP.php';

// Désactiver l'affichage des erreurs pour éviter les avertissements
ini_set('display_errors', 0);
error_reporting(0);

include_once '../base/base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];
    $created_at = date('Y-m-d H:i:s');

    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
        exit;
    }

    // Hachage du mot de passe avant insertion
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Génération du code de vérification à 6 chiffres
    $verification_code = random_int(100000, 999999);

    $sql = "INSERT INTO users (username, email, password, verification_code, created_at, role) 
            VALUES (:username, :email, :password, :verification_code, :created_at, :role)";
    $stmt = $pdo->prepare($sql);

    try {
        // Exécution de l'insertion
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashed_password,
            ':verification_code' => $verification_code,
            ':created_at' => $created_at,
            ':role' => $role  // Inclure le rôle dans la requête
        ]);

        // Envoi de l'e-mail de vérification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'master.quizz.info@gmail.com';
            $mail->Password = 'ccse fnic xuod iiyw';  // Assure-toi de ne pas le stocker en clair
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('master.quizz.info@gmail.com');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Vérification de votre adresse email';
            $mail->Body = "Bonjour $username,<br><br>Merci de vous être inscrit. Veuillez utiliser le code suivant pour vérifier votre adresse email : <strong>$verification_code</strong><br>.";

            $mail->send();
            header('location:verify.php?email=' . urlencode($email));
            exit;
        } catch (Exception $e) {
            echo "L'email de vérification n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'inscription : " . $e->getMessage();
    }
} else {
    echo "Méthode de requête non autorisée.";
}
?>