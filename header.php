<?php
if (session_status() === PHP_SESSION_NONE) session_start();
function require_login() {
    if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'gusmini') {
        header('Location: index.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gusmini Gym</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <h1>Gusmini Gym - Pannello</h1>
        <?php if (isset($_SESSION['user']) && $_SESSION['user'] === 'gusmini'): ?>
            <nav class="main-nav">
                <a href="dashboard.php">Home</a>
                <a href="aggiungi_iscrizione.php">Nuova Iscrizione</a>
                <a href="view_course_members.php">Iscritti per corso</a>
                <a href="full_report.php">Report completo</a>
            </nav>
        <?php endif; ?>
    </div>
</header>
<main class="container">

