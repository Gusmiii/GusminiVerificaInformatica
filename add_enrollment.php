<?php include 'header.php'; require_login(); require_once 'db.php'; ?>
<?php
$pdo = getPDO();
$message = '';
$istruttori = $pdo->query('SELECT id_istruttore, nome, cognome FROM Istruttori ORDER BY cognome, nome')->fetchAll();
$corsi = $pdo->query('SELECT id_corso, nome_corso, id_istruttore FROM Corsi ORDER BY nome_corso')->fetchAll();
$membri = $pdo->query('SELECT id_membro, nome, cognome FROM Membri ORDER BY cognome, nome')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_istr = intval(isset($_POST['id_istruttore']) ? $_POST['id_istruttore'] : 0);
    $id_corso = intval(isset($_POST['id_corso']) ? $_POST['id_corso'] : 0);
    $id_membro = intval(isset($_POST['id_membro']) ? $_POST['id_membro'] : 0);
    $orario = isset($_POST['orario']) ? $_POST['orario'] : null;

    $stmt = $pdo->prepare('SELECT id_istruttore FROM Corsi WHERE id_corso = ?');
    $stmt->execute([$id_corso]);
    $row = $stmt->fetch();
    if (!$row || intval($row['id_istruttore']) !== $id_istr) {
        $message = 'Errore: il corso selezionato non è tenuto dall\'istruttore indicato.';
    } else {
        // Inseriamo iscrizione
        $stmt = $pdo->prepare('INSERT INTO Iscrizioni_Corsi (id_corso, id_membro, data_iscrizione, orario_preferito) VALUES (?, ?, ?, ?)');
        $today = date('Y-m-d');
        try {
            $stmt->execute([$id_corso, $id_membro, $today, $orario]);
            $message = 'Iscrizione aggiunta con successo.';
        } catch (PDOException $e) {
            $message = 'Errore inserimento: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<div class="card">
    <h2>Nuova Iscrizione</h2>
    <?php if ($message): ?><div class="muted"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>
    <form method="post">
        <div class="row">
            <div>
                <label>Istruttore</label>
                <select id="id_istruttore" name="id_istruttore" required>
                    <option value="">-- seleziona --</option>
                    <?php foreach ($istruttori as $i): ?>
                        <option value="<?php echo $i['id_istruttore']; ?>"><?php echo htmlspecialchars($i['cognome'] . ' ' . $i['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Corso (filtrato)</label>
                <select id="id_corso" name="id_corso" required>
                    <option value="">-- seleziona istruttore prima --</option>
                    <?php foreach ($corsi as $c): ?>
                        <option data-istruttore="<?php echo $c['id_istruttore']; ?>" value="<?php echo $c['id_corso']; ?>"><?php echo htmlspecialchars($c['nome_corso']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Membro</label>
                <select name="id_membro" required>
                    <option value="">-- seleziona --</option>
                    <?php foreach ($membri as $m): ?>
                        <option value="<?php echo $m['id_membro']; ?>"><?php echo htmlspecialchars($m['cognome'] . ' ' . $m['nome']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Orario preferito</label>
                <input type="time" name="orario">
            </div>
        </div>
        <div style="margin-top:0.8rem;"><input type="submit" value="Aggiungi iscrizione"></div>
    </form>
</div>

<script>

    document.getElementById('id_istruttore').addEventListener('change', function(){
        var istr = this.value;
        var options = document.querySelectorAll('#id_corso option');
        options.forEach(function(opt){
            var data = opt.getAttribute('data-istruttore');
            if (!data) return; // leave the header option
            if (data === istr) {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        });

        document.getElementById('id_corso').value = '';
    });
</script>

<?php include 'footer.php'; ?>

