<?php include 'header.php'; require_login(); require_once 'db.php'; ?>
<?php
$pdo = getPDO();
$courses = $pdo->query('SELECT id_corso, nome_corso FROM Corsi ORDER BY nome_corso')->fetchAll();
$selected = intval(isset($_GET['id_corso']) ? $_GET['id_corso'] : 0);
$members = [];
if ($selected) {
    $stmt = $pdo->prepare('SELECT ic.id_iscrizione, m.id_membro, m.nome, m.cognome, ic.orario_preferito, ic.data_iscrizione FROM Iscrizioni_Corsi ic JOIN Membri m ON ic.id_membro = m.id_membro WHERE ic.id_corso = ? ORDER BY m.cognome, m.nome');
    $stmt->execute([$selected]);
    $members = $stmt->fetchAll();
}
?>

<div class="card">
    <h2>Iscritti per corso</h2>
    <form method="get">
        <label>Seleziona corso</label>
        <select name="id_corso" onchange="this.form.submit()">
            <option value="">-- seleziona --</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?php echo $c['id_corso']; ?>" <?php echo ($selected === intval($c['id_corso'])) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['nome_corso']); ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selected): ?>
        <h3 class="muted">Elenco iscritti</h3>
        <?php if (count($members) === 0): ?>
            <div class="muted">Nessun iscritto per questo corso.</div>
        <?php else: ?>
            <table>
                <thead><tr><th>Nome</th><th>Data iscrizione</th><th>Orario</th><th>Azioni</th></tr></thead>
                <tbody>
                <?php foreach ($members as $m): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($m['cognome'] . ' ' . $m['nome']); ?></td>
                        <td><?php echo htmlspecialchars($m['data_iscrizione']); ?></td>
                        <td><?php echo htmlspecialchars($m['orario_preferito']); ?></td>
                        <td class="actions"><a href="change_course.php?id_iscrizione=<?php echo $m['id_iscrizione']; ?>">Cambia corso</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

