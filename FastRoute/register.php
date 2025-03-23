<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connessione al database
    $conn = getConnection();

    // Controllo se l'email è già registrata
    $stmt = $conn->prepare("SELECT * FROM Utente WHERE Email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "Email già registrata. Per favore, usa un'altra email.";
    } else {
        // Hash della password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Inserimento del nuovo utente nel database
        $stmt = $conn->prepare("INSERT INTO Utente (Nome, Email, Password) VALUES (?, ?, ?)");
        if ($stmt->execute([$nome, $email, $hashedPassword])) {
            echo "Registrazione avvenuta con successo. Puoi ora effettuare il login.";
        } else {
            echo "Errore nella registrazione: " . $stmt->errorInfo()[2];
        }
    }

    $conn = null; // Chiudi la connessione
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
</head>
<body>
<h1>Registrazione</h1>
<form method="POST">
    Nome: <input type="text" name="nome" required>
    Email: <input type="email" name="email" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Registrati">
</form>
</body>
</html>
