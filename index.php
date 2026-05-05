<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = isset($_POST['user']) ? $_POST['user'] : '';
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
    if ($user === 'gusmini' && $pass === 'verifica') {
        // login OK
        $_SESSION['user'] = 'gusmini';
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Credenziali non valide.';
    }
}
?>
<?php include 'header.php'; ?>
<div class="card">
    <h2>Accesso</h2>
    <?php if ($error): ?><div class="muted"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="post">
        <div class="row">
            <div>
                <label>Nome utente</label>
                <input type="text" name="user" required placeholder="gusmini">
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="pass" required placeholder="verifica">
            </div>
        </div>
        <div style="margin-top:0.8rem;">
            <input type="submit" value="Accedi">
        </div>
    </form>
    <p class="muted">Per importare il DB: <a href="import_sql.php">Importa Verifica.sql</a></p>
</div>
<?php include 'footer.php'; ?>

