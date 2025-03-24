<?php
session_start();
require 'db.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice = $_POST['codice_univoco'];
    $dataRitiro = date('Y-m-d H:i:s');

    $conn = getConnection();
    $stmt = $conn->prepare("UPDATE Spedizione SET Stato = 'ritirato', DataRitiro = ? WHERE CodiceUnivoco = ?");

    if ($stmt->execute([$dataRitiro, $codice])) {
        $stmt = $conn->prepare("SELECT Stato FROM Spedizione WHERE CodiceUnivoco = ?");
        $stmt->execute([$codice]);
        $spedizione = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($spedizione) {
            echo "Ritiro registrato con successo per il codice: $codice. Stato attuale: " . htmlspecialchars($spedizione['Stato']);
        } else {
            echo "Errore: non è stato possibile recuperare lo stato della spedizione.";
        }
    } else {
        echo "Errore durante la registrazione del ritiro: " . $stmt->errorInfo()[2];
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
    <title>Registra Ritiro</title>
</head>
<body>
<h1>Registra Ritiro</h1>
<form method="POST">
    Codice Univoco: <input type="text" name="codice_univoco" required>
    <input type="submit" value="Registra Ritiro">
</form>
</body>
<?= require 'footer.php'; ?>
</html>