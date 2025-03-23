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
            <td><?= $spedizione['CodiceUnivoco'] ?></td>
            <td><?= $spedizione['MittenteNome'] ?> <?= $spedizione['MittenteCognome'] ?></td>
            <td><?= $spedizione['DestinatarioNome'] ?> <?= $spedizione['DestinatarioCognome'] ?></td>
            <td><?= $spedizione['DataSpedizione'] ?></td>
            <td><?= $spedizione['DataRitiro'] ?? 'N/A' ?></td>
            <td><?= $spedizione['DataConsegna'] ?? 'N/A' ?></td>
            <td><?= $spedizione['Stato'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php require 'footer.php'?>