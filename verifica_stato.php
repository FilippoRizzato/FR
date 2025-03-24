<?php
session_start();
require 'db.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice = $_POST['codice_univoco'];

    $conn = getConnection();
    $stmt = $conn->prepare("
        SELECT 
            s.CodiceUnivoco,
            m.Nome AS MittenteNome,
            m.Cognome AS MittenteCognome,
            d.Nome AS DestinatarioNome,
            d.Cognome AS DestinatarioCognome,
            s.Stato,
            s.DataSpedizione,
            s.DataRitiro,
            s.DataConsegna
        FROM Spedizione s
        INNER JOIN Cliente m ON s.MittenteID = m.ID
        INNER JOIN Cliente d ON s.DestinatarioID = d.ID
        WHERE s.CodiceUnivoco = ?
    ");
    $stmt->execute([$codice]);
    $spedizione = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($spedizione) {
        echo "<h2>Dettagli Spedizione</h2>";
        echo "Codice: " . htmlspecialchars($spedizione['CodiceUnivoco']) . "<br>";
        echo "Mittente: " . htmlspecialchars($spedizione['MittenteNome'] . " " . $spedizione['MittenteCognome']) . "<br>";
        echo "Destinatario: " . htmlspecialchars($spedizione['DestinatarioNome'] . " " . $spedizione['DestinatarioCognome']) . "<br>";
        echo "Stato: " . htmlspecialchars($spedizione['Stato']) . "<br>";
        echo "Data Spedizione: " . htmlspecialchars($spedizione['DataSpedizione']) . "<br>";
        echo "Data Ritiro: " . htmlspecialchars($spedizione['DataRitiro'] ?? 'N/A') . "<br>";
        echo "Data Consegna: " . htmlspecialchars($spedizione['DataConsegna'] ?? 'N/A') . "<br>";
    } else {
        echo "Nessuna spedizione trovata con il codice: " . htmlspecialchars($codice);
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
    <title>Verifica Stato Spedizione</title>
</head>
<body>
<h1>Verifica Stato Spedizione</h1>
<form method="POST">
    Codice Univoco: <input type="text" name="codice_univoco" required>
    <input type="submit" value="Verifica Stato">
</form>
</body>
<?= require 'footer.php'; ?>
</html>