<?php
// db.php
function getConnection() {
    $host = 'localhost';
    $db = 'fastroute'; // Sostituisci con il tuo nome del database
    $user = 'root'; // Sostituisci con il tuo nome utente
    $pass = ''; // Sostituisci con la tua password
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, $user, $pass, $options);
}
?>
