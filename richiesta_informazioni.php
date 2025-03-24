<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $messaggio = $_POST['messaggio'];

    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO RichiestaInformazioni (Nome, Email, Messaggio) VALUES (?, ?, ?)");
    if ($stmt->execute([$nome, $email, $messaggio])) {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'rizzato.filippo@iisviolamarchesini.edu.it';
            $mail->Password = 'fyxg yafz flej bwbh ';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('rizzato.filippo@iisviolamarchesini.edu.it', 'FastRoute');
            $mail->addAddress($email);
            $mail->Subject = 'Conferma Richiesta di Informazioni';
            $mail->Body = "Ciao $nome,\n\nGrazie per averci contattato! Abbiamo ricevuto la tua richiesta:\n\n$messaggio\n\nTi risponderemo al più presto.";
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $mail->send();
            echo "Richiesta inviata con successo! Ti abbiamo inviato un'email di conferma.";
        } catch (Exception $e) {
            echo "Errore durante l'invio dell'email: {$mail->ErrorInfo}";
        }
    } else {
        echo "Errore durante l'invio della richiesta: " . $stmt->errorInfo()[2];
    }
}

?>