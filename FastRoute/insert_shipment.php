<?php
session_start();
require 'db.php';
require 'header.php';
// Get the database connection
$conn = getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codice = $_POST['codice_univoco'];
    $mittenteNome = $_POST['mittente_nome'];
    $mittenteCognome = $_POST['mittente_cognome'];
    $destinatarioNome = $_POST['destinatario_nome'];
    $destinatarioCognome = $_POST['destinatario_cognome'];
    $dataSpedizione = date('Y-m-d H:i:s');

    // Trova o crea mittente
    $stmt = $conn->prepare("INSERT IGNORE INTO Cliente (Nome, Cognome) VALUES (?, ?)");
    $stmt->execute([$mittenteNome, $mittenteCognome]);
    $mittenteID = $conn->lastInsertId() ?: $conn->query("SELECT ID FROM Cliente WHERE Nome = '$mittenteNome' AND Cognome = '$mittenteCognome'")->fetchColumn();

    // Trova o crea destinatario
    $stmt->execute([$destinatarioNome, $destinatarioCognome]);
    $destinatarioID = $conn->lastInsertId() ?: $conn->query("SELECT ID FROM Cliente WHERE Nome = '$destinatarioNome' AND Cognome = '$destinatarioCognome'")->fetchColumn();

    // Inserisci spedizione
    $stmt = $conn->prepare("INSERT INTO Spedizione (CodiceUnivoco, MittenteID, DestinatarioID, DataSpedizione) VALUES (?, ?, ?, ?)");
    $stmt->execute([$codice, $mittenteID, $destinatarioID, $dataSpedizione]);

    echo "Spedizione registrata!";
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
    Mittente (Nome): <input type="text" name="mittente_nome" required>
    Mittente (Cognome): <input type="text" name="mittente_cognome" required>
    Destinatario (Nome): <input type="text" name="destinatario_nome" required>
    Destinatario (Cognome): <input type="text" name="destinatario_cognome" required>
    <input type="submit" value="Registra">
</form>
<?php require 'footer.php'?>