<?php

$db_host = '127.0.0.1';
$db_name = 'gusmini_gym';
$db_user = 'root';
$db_pass = '';
$db_charset = 'utf8mb4';

function getPDO($withDb = true) {
    global $db_host, $db_name, $db_user, $db_pass, $db_charset;
    $dsn = 'mysql:host=' . $db_host . ($withDb ? ';dbname=' . $db_name : '') . ';charset=' . $db_charset;
    try {
        $pdo = new PDO($dsn, $db_user, $db_pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die('Errore connessione DB: ' . htmlspecialchars($e->getMessage()));
    }
}
?>

