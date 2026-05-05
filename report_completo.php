<?php include 'header.php'; require_login(); require_once 'db.php'; ?>
<?php
$pdo = getPDO();
$istruttori = $pdo->query('SELECT id_istruttore, nome, cognome FROM Istruttori ORDER BY cognome, nome')->fetchAll();
?>

<div class="card">
    <h2>Report completo: corsi per istruttore e iscritti</h2>
    <?php foreach ($istruttori as $i): ?>
        <section style="margin-bottom:1rem;">
            <h3><?php echo htmlspecialchars($i['cognome'] . ' ' . $i['nome']); ?></h3>
            <?php
            $stmt = $pdo->prepare('SELECT id_corso, nome_corso FROM Corsi WHERE id_istruttore = ? ORDER BY nome_corso');
            $stmt->execute([$i['id_istruttore']]);
            $corsi = $stmt->fetchAll();
            ?>
            <?php if (count($corsi) === 0): ?>
                <div class="muted">Nessun corso per questo istruttore.</div>
            <?php else: ?>
                <?php foreach ($corsi as $c): ?>
                    <div style="padding:0.6rem; border-left:4px solid #eef3fb; margin-bottom:0.6rem;">
                        <strong><?php echo htmlspecialchars($c['nome_corso']); ?></strong>
                        <?php
                        $s2 = $pdo->prepare('SELECT m.nome, m.cognome, ic.data_iscrizione, ic.orario_preferito FROM Iscrizioni_Corsi ic JOIN Membri m ON ic.id_membro = m.id_membro WHERE ic.id_corso = ? ORDER BY m.cognome, m.nome');
                        $s2->execute([$c['id_corso']]);
                        $membri = $s2->fetchAll();
                        ?>
                        <?php if (count($membri) === 0): ?>
                            <div class="muted">Nessun iscritto</div>
                        <?php else: ?>
                            <table>
                                <thead><tr><th>Nome</th><th>Data iscrizione</th><th>Orario</th></tr></thead>
                                <tbody>
                                <?php foreach ($membri as $mm): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($mm['cognome'] . ' ' . $mm['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($mm['data_iscrizione']); ?></td>
                                        <td><?php echo htmlspecialchars($mm['orario_preferito']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    <?php endforeach; ?>
</div>

<?php include 'footer.php'; ?>

