<?php
$page_titre = 'Mes factures';
require_once __DIR__ . '/../includes/auth.php';
verifier_client();
require_once __DIR__ . '/../includes/connexion.php';
require_once __DIR__ . '/includes/header.php';

$id = $_SESSION['id_client'];
?>

<h2>Mes factures</h2>

<table>
<thead><tr><th>N</th><th>Date</th><th>Mode</th><th>Montant</th><th>Statut</th></tr></thead>
<tbody>
<?php
$st = $pdo->prepare("SELECT * FROM FACTURATION WHERE id_client=? ORDER BY date_facture DESC");
$st->execute([$id]);
foreach ($st->fetchAll() as $f): ?>
<tr>
    <td><?= $f['id_facture'] ?></td>
    <td><?= date('d/m/Y', strtotime($f['date_facture'])) ?></td>
    <td><?= $f['mode_facturation'] ?></td>
    <td><?= number_format($f['montant_total'],2,',',' ') ?> EUR</td>
    <td><?= $f['est_payee'] ? '<span class="text-success">Payee</span>' : '<span class="text-danger">Impayee</span>' ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
