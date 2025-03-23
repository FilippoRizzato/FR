<?php
session_start();
require 'db.php';
require 'header.php';
// Get the database connection
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days = intval($_POST['days']);

    // Query to get the total number of deliveries and deliveries per day for the last N days
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

    // Calculate total deliveries
    $totalDeliveries = array_sum(array_column($deliveries, 'DeliveryCount'));

    // Display results
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
<form method="POST">
    Numero di giorni: <input type="number" name="days" required min="1">
    <input type="submit" value="Cerca Consegne">
</form>
<?php require 'footer.php'?>