<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $spedizioneID = $_POST['spedizione_id'];

    // Connessione al database
    $conn = getConnection();

    // Query per ottenere lo stato della spedizione
    $stmt = $conn->prepare("SELECT Stato FROM Spedizione WHERE ID = ?");
    $stmt->execute([$spedizioneID]);
    $stato = $stmt->fetchColumn();

    if ($stato) {
        echo "Lo stato della spedizione ID $spedizioneID è: $stato";
    } else {
        echo "Nessuna spedizione trovata con l'ID $spedizioneID.";
    }
}
?>
<form method="POST">
    ID Spedizione: <input type="number" name="spedizione_id" required>
    <input type="submit" value="Verifica Stato">
</form>
