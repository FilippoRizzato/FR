<?php
session_start();
require 'db.php';
require 'header.php';
// Get the database connection
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice = $_POST['codice_univoco'];
    $dataRitiro = date('Y-m-d H:i:s');

    // Aggiorna stato e data ritiro
    $stmt = $conn->prepare("
        UPDATE Spedizione 
        SET Stato = 'in transito', DataRitiro = ? 
        WHERE CodiceUnivoco = ?
    ");

    if ($stmt->execute([$dataRitiro, $codice])) {
        echo "Ritiro registrato per il codice: $codice";
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
<form method="POST">
    Codice Univoco: <input type="text" name="codice_univoco" required>
    <input type="submit" value="Registra Ritiro">
</form>
<?php require 'footer.php'?>
