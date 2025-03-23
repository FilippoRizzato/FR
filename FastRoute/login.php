<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $conn = getConnection();

    $stmt = $conn->prepare("SELECT * FROM Utente WHERE Email = ?");
    $stmt->execute([$email]);
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utente) {
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $utente['ID'];
            if ($rememberMe) {
                setcookie('user_id', $utente['ID'], time() + (86400 * 30), "/"); // 30 giorni
            }
            header("Location: home_page.php");
            exit;
        } else {
            echo "Credenziali non valide: password errata.";
        }
    } else {
        echo "Credenziali non valide: email non trovata.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<form method="POST">
    Email: <input type="email" name="email" required>
    Password: <input type="password" name="password" required>
    <label><input type="checkbox" name="remember_me"> Ricordami</label>
    <input type="submit" value="Login">
</form>
</body>
</html>