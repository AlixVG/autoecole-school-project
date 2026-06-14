<?php
$page_titre = 'Facturation';
require_once __DIR__ . '/../includes/auth.php';
verifier_admin();
require_once __DIR__ . '/../includes/connexion.php';
$msg = ''; $err = '';

if (isset($_GET['sup'])) {
    try { $pdo->prepare("DELETE FROM FACTURATION WHERE id_facture=?")->execute([$_GET['sup']]); $msg = "Supprimé"; }
    catch (Exception $e) { $err = "Impossible de supprimer cette facture."; }
}
if (isset($_GET['payer'])) {
    try { $pdo->prepare("UPDATE FACTURATION SET est_payee=1 WHERE id_facture=?")->execute([$_GET['payer']]); $msg = "Facture payée."; }
    catch (Exception $e) { $err = "Erreur lors de la mise à jour."; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->prepare("INSERT INTO FACTURATION (id_client, date_facture, mode_facturation, montant_total, est_payee) VALUES (?,?,?,?,?)")
            ->execute([$_POST['id_client'], $_POST['date_facture'], $_POST['mode_facturation'], $_POST['montant_total'], isset($_POST['est_payee']) ? 1 : 0]);
        $msg = "Facture créée.";
    } catch (Exception $e) {
        $err = "Erreur lors de la creation de la facture.";
    }
}

require_once __DIR__ . '/includes/header.php';
$clients = $pdo->query("SELECT * FROM CLIENT ORDER BY nom_client")->fetchAll();
?>

<h2>Facturation</h2>

<h3>Synthese</h3>
<div class="dash">
<?php foreach ($pdo->query("SELECT mode_facturation, COUNT(*) as nb, SUM(montant_total) as total, SUM(CASE WHEN est_payee THEN montant_total ELSE 0 END) as paye FROM FACTURATION GROUP BY mode_facturation")->fetchAll() as $s): ?>
<div class="card"><div class="nb"><?= number_format($s['total'],2,',',' ') ?> EUR</div><div class="lb"><?= $s['mode_facturation'] ?> (<?= $s['nb'] ?>)<br>Paye: <?= number_format($s['paye'],2,',',' ') ?> EUR</div></div>
<?php endforeach; ?>
</div>

<h3>Créer une facture</h3>
<div class="fb"><form method="POST">
    <div class="fr">
        <div><label>Client *</label>
            <select name="id_client" required><option value="">--</option>
            <?php foreach($clients as $c): ?><option value="<?= $c['id_client'] ?>"><?= $c['nom_client'].' '.$c['prenom_client'] ?></option><?php endforeach; ?>
            </select>
        </div>
        <div><label>Date *</label><input type="date" name="date_facture" value="<?= date('Y-m-d') ?>" required></div>
    </div>
    <div class="fr">
        <div><label>Mode *</label>
            <select name="mode_facturation"><option value="heure">A l'heure</option><option value="forfait_pack">Forfait Pack</option><option value="forfait_global">Forfait Global</option></select>
        </div>
        <div><label>Montant (EUR) *</label><input type="number" name="montant_total" step="0.01" required></div>
    </div>
    <label><input type="checkbox" name="est_payee" value="1"> Deja payee</label><br><br>
    <button class="btn bp">Creer</button>
</form></div>

<h3>Factures</h3>
<table>
<thead><tr><th>N</th><th>Client</th><th>Date</th><th>Mode</th><th>Montant</th><th>Statut</th><th></th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT f.*, c.nom_client, c.prenom_client FROM FACTURATION f JOIN CLIENT c ON f.id_client=c.id_client ORDER BY f.date_facture DESC")->fetchAll() as $f): ?>
<tr>
    <td><?= $f['id_facture'] ?></td>
    <td><?= $f['prenom_client'].' '.$f['nom_client'] ?></td>
    <td><?= date('d/m/Y', strtotime($f['date_facture'])) ?></td>
    <td><?= $f['mode_facturation'] ?></td>
    <td><?= number_format($f['montant_total'],2,',',' ') ?> EUR</td>
    <td><?= $f['est_payee'] ? '<span class="text-success">Payee</span>' : '<span class="text-danger">Impayee</span>' ?></td>
    <td>
        <?php if (!$f['est_payee']): ?><a href="?payer=<?= $f['id_facture'] ?>" class="btn bs bsm">Payer</a><?php endif; ?>
        <a href="?sup=<?= $f['id_facture'] ?>" class="btn bd bsm" onclick="return confirm('Supprimer ?')">X</a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
