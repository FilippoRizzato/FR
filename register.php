<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!isValidPassword($password)) {
        echo "La password deve contenere almeno 8 caratteri, inclusi lettere maiuscole, lettere minuscole, numeri e caratteri speciali.";
    } else {
        $conn = getConnection();

        $stmt = $conn->prepare("SELECT * FROM Utente WHERE Email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            echo "Email già registrata. Per favore, usa un'altra email.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO Utente (Nome, Email, Password) VALUES (?, ?, ?)");
            if ($stmt->execute([$nome, $email, $hashedPassword])) {
                header("Location: login.php");
                exit;
            } else {
                echo "Errore nella registrazione: " . $stmt->errorInfo()[2];
            }
        }

        $conn = null;
    }
}

function isValidPassword($password) {
    if (strlen($password) < 8) {
        return false;
    }
    if (!preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[\W_]/', $password)) {
        return false;
    }
    return true;
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
<?= require 'footer.php'; ?>
</html>