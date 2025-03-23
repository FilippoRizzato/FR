<?php
session_start();
require 'db.php';
require 'header.php';

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Gestione del colore del tema
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

</head>
<body>
<div class="container">

    <style>
        body {
        <?php if (isset($_SESSION['theme_color'])): ?>
            background-color: <?php echo htmlspecialchars($_SESSION['theme_color']); ?>;
        <?php endif; ?>
        }
    </style>
    <form method="POST" style="margin-top: 20px;">
        <label for="theme_color">Seleziona il colore del tema:</label>
        <input type="color" name="theme_color" id="theme_color" value="<?php echo isset($_SESSION['theme_color']) ? htmlspecialchars($_SESSION['theme_color']) : '#ffffff'; ?>">
        <input type="submit" value="Cambia Colore">
    </form>
</div>
</body>
<?= require 'footer.php';?>
</html>