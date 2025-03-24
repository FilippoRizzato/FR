<?php
session_start();
require 'db.php';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days = intval($_POST['days']);
    $conn = getConnection();

    $stmt = $conn->prepare("
        SELECT 
            DATE(DataConsegna) AS DeliveryDate,
            COUNT(*) AS DeliveryCount
        FROM Spedizione
        WHERE DataConsegna >= NOW() - INTERVAL ? DAY
        GROUP BY DeliveryDate
        ORDER BY DeliveryDate DESC
    ");

    $stmt->execute([$days]);
    $deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalDeliveries = array_sum(array_column($deliveries, 'DeliveryCount'));

    echo "<h2>Consegne negli ultimi $days giorni</h2>";
    echo "Numero totale di consegne: $totalDeliveries<br>";
    echo "<table border='1'>
            <tr>
                <th>Data Consegna</th>
                <th>Numero di Consegne</th>
            </tr>";

    foreach ($deliveries as $delivery) {
        echo "<tr>
                <td>" . htmlspecialchars($delivery['DeliveryDate']) . "</td>
                <td>" . htmlspecialchars($delivery['DeliveryCount']) . "</td>
              </tr>";
    }

    echo "</table>";
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
    <title>Cerca Consegne</title>
</head>
<body>
<h1>Cerca Consegne</h1>
<form method="POST">
    <input type="number" name="days" required min="1">

    <input type="submit" value="Cerca Consegne">

</form>

</body>
<?= require 'footer.php'; ?>
</html>