<?php
session_start();
require 'db.php';
require 'header.php';

$conn = getConnection();
$stmt = $conn->prepare("
    SELECT 
        s.CodiceUnivoco,
        m.Nome AS MittenteNome,
        m.Cognome AS MittenteCognome,
        d.Nome AS DestinatarioNome,
        d.Cognome AS DestinatarioCognome,
        s.DataSpedizione,
        s.DataRitiro,
        s.DataConsegna,
        s.Stato
    FROM Spedizione s
    INNER JOIN Cliente m ON s.MittenteID = m.ID
    INNER JOIN Cliente d ON s.DestinatarioID = d.ID
");

$stmt->execute();
$spedizioni = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Dashboard Spedizioni</title>
</head>
<body>
<h1>Dashboard Spedizioni</h1>
<table border="1">
    <tr>
        <th>Codice</th>
        <th>Mittente</th>
        <th>Destinatario</th>
        <th>Data Spedizione</th>
        <th>Data Ritiro</th>
        <th>Data Consegna</th>
        <th>Stato</th>
    </tr>
    <?php foreach ($spedizioni as $spedizione): ?>
        <tr>
            <td><?= htmlspecialchars($spedizione['CodiceUnivoco']) ?></td>
            <td><?= htmlspecialchars($spedizione['MittenteNome'] . " " . $spedizione['MittenteCognome']) ?></td>
            <td><?= htmlspecialchars($spedizione['DestinatarioNome'] . " " . $spedizione['DestinatarioCognome']) ?></td>
            <td><?= htmlspecialchars($spedizione['DataSpedizione']) ?></td>
            <td><?= htmlspecialchars($spedizione['DataRitiro'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($spedizione['DataConsegna'] ?? 'N/A') ?></td>
            <td><?= htmlspecialchars($spedizione['Stato']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
<?= require 'footer.php'; ?>
</html>