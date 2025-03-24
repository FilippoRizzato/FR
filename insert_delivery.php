<?php
session_start();
require 'db.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice = $_POST['codice_univoco'];
    $dataConsegna = date('Y-m-d H:i:s');

    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE Spedizione SET Stato = 'consegnato', DataConsegna = ? WHERE CodiceUnivoco = ?");

    if ($stmt->execute([$dataConsegna, $codice])) {
        echo "Consegna registrata con successo per il codice: $codice";
    } else {
        echo "Errore durante la registrazione della consegna: " . $stmt->errorInfo()[2];
    }
}
?>
<style>
    body {
    <?php if (isset($_SESSION['theme_color'])): ?>
        background-color: <?php echo htmlspecialchars($_SESSION['theme_color']); ?>;
    <?php endif; ?>
    }
</style>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registra Consegna</title>
</head>
<body>
<h1>Registra Consegna</h1>
<form method="POST">
    Codice Univoco: <input type="text" name="codice_univoco" required>
    <input type="submit" value="Registra Consegna">
</form>
</body>
<?= require 'footer.php'; ?>
</html>