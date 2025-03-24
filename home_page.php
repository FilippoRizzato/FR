<?php
session_start();
require 'db.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme_color'])) {
    $_SESSION['theme_color'] = $_POST['theme_color'];
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastRoute - Home</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
        <?php if (isset($_SESSION['theme_color'])): ?>
            background-color: <?= htmlspecialchars($_SESSION['theme_color']); ?>;
        <?php else: ?>
            background-color: #f4f4f4;
        <?php endif; ?>
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Benvenuto in FastRoute</h1>
    <p>Il tuo sistema di gestione delle spedizioni.</p>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="auth-buttons">
            <a href="login.php" class="button">Login</a>
            <a href="register.php" class="button">Registrati</a>
        </div>

        <form method="POST" action="richiesta_informazioni.php" style="margin-top: 20px;">
            <h3>Richiesta Informazioni</h3>
            Nome: <input type="text" name="nome" required>
            Email: <input type="email" name="email" required>
            Messaggio: <textarea name="messaggio" required></textarea>
            <input type="submit" value="Invia Richiesta" class="button">
        </form>
    <?php else: ?>
        <h2>Benvenuto, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Utente') ?>!</h2>
        <form method="POST" action="logout.php" style="display:inline;">
            <input type="submit" value="Logout" class="button">
        </form>

        <form method="POST" style="margin-top: 20px;">
            <label for="theme_color">Seleziona il colore del tema:</label>
            <input type="color" name="theme_color" id="theme_color" value="<?= isset($_SESSION['theme_color']) ? htmlspecialchars($_SESSION['theme_color']) : '#ffffff'; ?>">
            <input type="submit" value="Cambia Colore" class="button">
        </form>
    <?php endif; ?>
</div>
</body>
<?= require 'footer.php'; ?>
</html>