<?php
require_once __DIR__ . '/db.php';

$sqlFile = __DIR__ . '/Verifica.sql';
if (!file_exists($sqlFile)) {
    die('File Verifica.sql non trovato in cartella del progetto.');
}

try {
    $tmpDsn = 'mysql:host=127.0.0.1;charset=utf8mb4';
    $pdoTmp = new PDO($tmpDsn, 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $dbName = 'gusmini_gym';
    $pdoTmp->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    echo "Database `$dbName` creato o già presente.<br>";


    $pdo = getPDO(true);
    $content = file_get_contents($sqlFile);

    $lines = explode("\n", $content);
    $clean = [];
    foreach ($lines as $line) {
        $trim = trim($line);
        if ($trim === '' || strpos($trim, '--') === 0) continue;
        $clean[] = $line;
    }
    $cleanSql = implode("\n", $clean);

    $statements = preg_split('/;\s*\n/', $cleanSql);
    $count = 0;
    foreach ($statements as $stmt) {
        $s = trim($stmt);
        if ($s === '') continue;
        try {
            $pdo->exec($s);
            $count++;
        } catch (PDOException $e) {
            echo "Errore eseguendo statement: " . htmlspecialchars($e->getMessage()) . "<br>";
        }
    }
    echo "Import completato. Statement eseguiti: $count<br>";
    echo '<a href="index.php">Vai alla login</a>';

} catch (PDOException $e) {
    die('Errore import: ' . htmlspecialchars($e->getMessage()));
}

?>

