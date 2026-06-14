<?php
$page_titre = 'Planning';
require_once __DIR__ . '/../includes/auth.php';
verifier_admin();
require_once __DIR__ . '/../includes/connexion.php';
$msg = ''; $err = '';
require_once __DIR__ . '/includes/header.php';

$moniteurs = $pdo->query("SELECT * FROM MONITEUR ORDER BY nom_moniteur")->fetchAll();
$fm = $_GET['moniteur'] ?? '';
$fd = $_GET['date'] ?? date('Y-m-d');
$ft = $_GET['type'] ?? 'jour';
?>

<h2>Planning</h2>

<div class="filtre">
<form method="GET">
    <div><label>Moniteur</label><br>
        <select name="moniteur"><option value="">-- Tous --</option>
        <?php foreach($moniteurs as $m): ?><option value="<?= $m['id_moniteur'] ?>" <?= $fm==$m['id_moniteur']?'selected':'' ?>><?= $m['nom_moniteur'].' '.$m['prenom_moniteur'] ?></option><?php endforeach; ?>
        </select>
    </div>
    <div><label>Date</label><br><input type="date" name="date" value="<?= $fd ?>"></div>
    <div><label>Vue</label><br>
        <select name="type"><option value="jour" <?= $ft=='jour'?'selected':'' ?>>Jour</option><option value="semaine" <?= $ft=='semaine'?'selected':'' ?>>Semaine</option></select>
    </div>
    <div><button class="btn bp">Filtrer</button></div>
</form>
</div>

<?php
$sql = "SELECT l.*, c.nom_client, c.prenom_client, m.nom_moniteur, m.prenom_moniteur, v.immatriculation, mv.marque, mv.nom_modele FROM LECON l JOIN CLIENT c ON l.id_client=c.id_client JOIN MONITEUR m ON l.id_moniteur=m.id_moniteur JOIN VOITURE v ON l.id_voiture=v.id_voiture JOIN MODELE_VOITURE mv ON v.id_modele=mv.id_modele WHERE 1=1";
$par = [];
if ($fm) { $sql .= " AND l.id_moniteur=?"; $par[] = $fm; }
if ($ft == 'jour') {
    $sql .= " AND l.date_lecon=?"; $par[] = $fd;
    $titre = "Planning du " . date('d/m/Y', strtotime($fd));
} else {
    $lu = date('Y-m-d', strtotime('monday this week', strtotime($fd)));
    $di = date('Y-m-d', strtotime('sunday this week', strtotime($fd)));
    $sql .= " AND l.date_lecon BETWEEN ? AND ?"; $par[] = $lu; $par[] = $di;
    $titre = "Semaine du " . date('d/m/Y', strtotime($lu)) . " au " . date('d/m/Y', strtotime($di));
}
$sql .= " ORDER BY l.date_lecon, l.heure_debut";
$st = $pdo->prepare($sql); $st->execute($par); $lecons = $st->fetchAll();
?>

<h3><?= $titre ?></h3>
<?php if (empty($lecons)): ?>
<p>Aucune lecon.</p>
<?php else: ?>
<table>
<thead><tr><?php if($ft=='semaine'):?><th>Date</th><?php endif;?><th>Heure</th><th>Duree</th><th>Client</th><th>Moniteur</th><th>Voiture</th><th>Km</th><th>Observation</th></tr></thead>
<tbody>
<?php foreach ($lecons as $l): ?>
<tr>
    <?php if($ft=='semaine'):?><td><?= date('D d/m', strtotime($l['date_lecon'])) ?></td><?php endif;?>
    <td><?= substr($l['heure_debut'],0,5) ?></td><td><?= $l['duree_minutes'] ?> min</td>
    <td><?= $l['prenom_client'].' '.$l['nom_client'] ?></td>
    <td><?= $l['prenom_moniteur'].' '.$l['nom_moniteur'] ?></td>
    <td><?= $l['immatriculation'].' ('.$l['marque'].' '.$l['nom_modele'].')' ?></td>
    <td><?= $l['km_parcourus'] ?></td><td><?= $l['observation'] ?? '' ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php endif; ?>

<h3>Kilometrage mensuel</h3>
<table>
<thead><tr><th>Periode</th><th>Voiture</th><th>Modele</th><th>Km debut</th><th>Km fin</th><th>Parcourus</th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT v.immatriculation, mv.marque, mv.nom_modele, mo.annee, mo.mois, km.km_debut_mois, km.km_fin_mois, (km.km_fin_mois - km.km_debut_mois) as kp FROM KM_MENSUEL km JOIN VOITURE v ON km.id_voiture=v.id_voiture JOIN MODELE_VOITURE mv ON v.id_modele=mv.id_modele JOIN MOIS mo ON km.id_mois=mo.id_mois ORDER BY mo.annee DESC, mo.mois DESC")->fetchAll() as $k): ?>
<tr>
    <td><?= sprintf('%02d/%d', $k['mois'], $k['annee']) ?></td>
    <td><?= $k['immatriculation'] ?></td><td><?= $k['marque'].' '.$k['nom_modele'] ?></td>
    <td><?= number_format($k['km_debut_mois'],0,',',' ') ?></td>
    <td><?= number_format($k['km_fin_mois'],0,',',' ') ?></td>
    <td><strong><?= number_format($k['kp'],0,',',' ') ?> km</strong></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
