<?php include 'header.php'; require_login(); require_once 'db.php'; ?>
<?php
$pdo = getPDO();
$id_iscr = intval(isset($_GET['id_iscrizione']) ? $_GET['id_iscrizione'] : (isset($_POST['id_iscrizione']) ? $_POST['id_iscrizione'] : 0));
if (!$id_iscr) {
    header('Location: view_course_members.php'); exit;
}

$stmt = $pdo->prepare('SELECT ic.*, m.nome, m.cognome, c.nome_corso AS corso_attuale FROM Iscrizioni_Corsi ic JOIN Membri m ON ic.id_membro = m.id_membro JOIN Corsi c ON ic.id_corso = c.id_corso WHERE ic.id_iscrizione = ?');
$stmt->execute([$id_iscr]);
$iscr = $stmt->fetch();
if (!$iscr) { echo 'Iscrizione non trovata.'; include 'footer.php'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_course = intval(isset($_POST['new_course']) ? $_POST['new_course'] : 0);
    if ($new_course) {
        $u = $pdo->prepare('UPDATE Iscrizioni_Corsi SET id_corso = ? WHERE id_iscrizione = ?');
        $u->execute([$new_course, $id_iscr]);
        header('Location: view_course_members.php?id_corso=' . $new_course);
        exit;
    }
}

$courses = $pdo->query('SELECT id_corso, nome_corso FROM Corsi ORDER BY nome_corso')->fetchAll();
?>

<div class="card">
    <h2>Cambia corso per <?php echo htmlspecialchars($iscr['cognome'] . ' ' . $iscr['nome']); ?></h2>
    <p class="muted">Corso attuale: <?php echo htmlspecialchars($iscr['corso_attuale']); ?></p>
    <form method="post">
        <input type="hidden" name="id_iscrizione" value="<?php echo $id_iscr; ?>">
        <label>Nuovo corso</label>
        <select name="new_course" required>
            <option value="">-- seleziona --</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?php echo $c['id_corso']; ?>" <?php echo ($c['id_corso'] == $iscr['id_corso']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['nome_corso']); ?></option>
            <?php endforeach; ?>
        </select>
        <div style="margin-top:0.8rem;"><input type="submit" value="Aggiorna"></div>
    </form>
</div>

<?php include 'footer.php'; ?>

