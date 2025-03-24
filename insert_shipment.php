<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: home_page.php");
    exit;
}
require 'db.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice = $_POST['codice_univoco'];
    $mittenteNome = $_POST['mittente_nome'];
    $mittenteCognome = $_POST['mittente_cognome'];
    $destinatarioNome = $_POST['destinatario_nome'];
    $destinatarioCognome = $_POST['destinatario_cognome'];
    $dataSpedizione = date('Y-m-d H:i:s');

    $conn = getConnection();


    $stmt = $conn->prepare("SELECT ID FROM Cliente WHERE Nome = ? AND Cognome = ?");
    $stmt->execute([$mittenteNome, $mittenteCognome]);
    $mittente = $stmt->fetch(PDO::FETCH_ASSOC);
    $mittenteID = $mittente ? $mittente['ID'] : null;


    $stmt = $conn->prepare("SELECT ID FROM Cliente WHERE Nome = ? AND Cognome = ?");
    $stmt->execute([$destinatarioNome, $destinatarioCognome]);
    $destinatario = $stmt->fetch(PDO::FETCH_ASSOC);
    $destinatarioID = $destinatario ? $destinatario['ID'] : null;

    if ($mittenteID && $destinatarioID) {
        $stmt = $conn->prepare("INSERT INTO Spedizione (CodiceUnivoco, MittenteID, DestinatarioID, Stato, DataSpedizione) VALUES (?, ?, ?, 'in partenza', ?)");
        $stmt->execute([$codice, $mittenteID, $destinatarioID, $dataSpedizione]);

        echo "Spedizione registrata con successo!";
    } else {
        echo "Errore: uno o più nomi non corrispondono a un ID valido.";
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
    <title>Inserisci Spedizione</title>
</head>
<body>
<h1>Inserisci Spedizione</h1>
<form method="POST">
    Codice Univoco: <input type="text" name="codice_univoco" required><br>
    Mittente Nome: <input type="text" name="mittente_nome" required><br>
    Mittente Cognome: <input type="text" name="mittente_cognome" required><br>
    Destinatario Nome: <input type="text" name="destinatario_nome" required><br>
    Destinatario Cognome: <input type="text" name="destinatario_cognome" required><br>
    <input type="submit" value="Registra Spedizione">
</form>
</body>
<?= require 'footer.php'; ?>
</html>