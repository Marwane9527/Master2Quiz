<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'master.quizz.info@gmail.com'; // addresse gmail
    $mail->Password = 'ccse fnic xuod iiyw'; //mot de passe app gmail
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('master.quizz.info@gmail.com
'); // addresse gmail
    $mail->addAddress($_POST["email"]);

    $mail->isHTML(true);

    $mail->Subject = $_POST["subject"];
    $mail->Body = $_POST["message"];


    $mail->send();

    echo
        "
    <script>
    alert('Transfert au serveur');
    document.location.href = 'index.php';
    </script>
    ";
}
?>