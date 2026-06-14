<?php
$page_titre = 'Tableau de bord';
require_once __DIR__ . '/../includes/auth.php';
verifier_admin();
require_once __DIR__ . '/../includes/connexion.php';
$msg = ''; $err = '';
require_once __DIR__ . '/includes/header.php';

$nb_c = $pdo->query("SELECT COUNT(*) FROM CLIENT")->fetchColumn();
$nb_m = $pdo->query("SELECT COUNT(*) FROM MONITEUR")->fetchColumn();
$nb_v = $pdo->query("SELECT COUNT(*) FROM VOITURE")->fetchColumn();
$nb_l = $pdo->query("SELECT COUNT(*) FROM LECON")->fetchColumn();
$nb_f = $pdo->query("SELECT COUNT(*) FROM FACTURATION")->fetchColumn();
$imp = $pdo->query("SELECT COALESCE(SUM(montant_total),0) FROM FACTURATION WHERE est_payee=0")->fetchColumn();
?>

<h2>Tableau de bord</h2>
<div class="dash">
    <div class="card"><div class="nb"><?= $nb_c ?></div><div class="lb">Clients</div></div>
    <div class="card"><div class="nb"><?= $nb_m ?></div><div class="lb">Moniteurs</div></div>
    <div class="card"><div class="nb"><?= $nb_v ?></div><div class="lb">Voitures</div></div>
    <div class="card"><div class="nb"><?= $nb_l ?></div><div class="lb">Lecons</div></div>
    <div class="card"><div class="nb"><?= $nb_f ?></div><div class="lb">Factures</div></div>
    <div class="card"><div class="nb"><?= number_format($imp,2,',',' ') ?> EUR</div><div class="lb">Impayes</div></div>
</div>

<h3>10 dernieres lecons</h3>
<table>
<thead><tr><th>Date</th><th>Heure</th><th>Duree</th><th>Client</th><th>Moniteur</th><th>Voiture</th></tr></thead>
<tbody>
<?php
$r = $pdo->query("SELECT l.date_lecon, l.heure_debut, l.duree_minutes, c.nom_client, c.prenom_client, m.nom_moniteur, m.prenom_moniteur, v.immatriculation FROM LECON l JOIN CLIENT c ON l.id_client=c.id_client JOIN MONITEUR m ON l.id_moniteur=m.id_moniteur JOIN VOITURE v ON l.id_voiture=v.id_voiture ORDER BY l.date_lecon DESC, l.heure_debut DESC LIMIT 10");
while ($l = $r->fetch()): ?>
<tr>
    <td><?= date('d/m/Y', strtotime($l['date_lecon'])) ?></td>
    <td><?= substr($l['heure_debut'],0,5) ?></td>
    <td><?= $l['duree_minutes'] ?> min</td>
    <td><?= $l['prenom_client'].' '.$l['nom_client'] ?></td>
    <td><?= $l['prenom_moniteur'].' '.$l['nom_moniteur'] ?></td>
    <td><?= $l['immatriculation'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
