<?php
session_start();
require 'db.php';
require 'header.php';
$conn = getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice = $_POST['codice_univoco'];
    $dataConsegna = date('Y-m-d H:i:s');

    // Aggiorna stato e data consegna
    $stmt = $conn->prepare("
        UPDATE Spedizione 
        SET Stato = 'consegnato', DataConsegna = ? 
        WHERE CodiceUnivoco = ?
    ");
    $stmt->execute([$dataConsegna, $codice]);

    echo "Consegna registrata per il codice: $codice";
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
    Codice Univoco: <input type="text" name="codice_univoco" required>
    <input type="submit" value="Registra Consegna">
</form>
<?php require 'footer.php'?>