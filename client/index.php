<?php
$page_titre = 'Mon espace';
require_once __DIR__ . '/../includes/auth.php';
verifier_client();
require_once __DIR__ . '/../includes/connexion.php';
require_once __DIR__ . '/includes/header.php';

$id = $_SESSION['id_client'];
$ci = $pdo->prepare("SELECT c.*, e.nom_etablissement FROM CLIENT c LEFT JOIN ETABLISSEMENT e ON c.id_etablissement=e.id_etablissement WHERE c.id_client=?");
$ci->execute([$id]); $ci = $ci->fetch();

$st = $pdo->prepare("SELECT COUNT(*) FROM LECON WHERE id_client=?"); $st->execute([$id]); $nb_lecons = $st->fetchColumn();
$st = $pdo->prepare("SELECT COALESCE(SUM(duree_minutes),0) FROM LECON WHERE id_client=?"); $st->execute([$id]); $total_h = round($st->fetchColumn() / 60, 1);
?>

<h2>Mon espace</h2>

<div class="dash">
    <div class="card"><div class="nb"><?= $ci['date_prevue_code'] ? date('d/m/Y', strtotime($ci['date_prevue_code'])) : '-' ?></div><div class="lb">Date prevue code</div></div>
    <div class="card"><div class="nb"><?= $ci['date_prevue_permis'] ? date('d/m/Y', strtotime($ci['date_prevue_permis'])) : '-' ?></div><div class="lb">Date prevue permis</div></div>
    <div class="card"><div class="nb"><?= $nb_lecons ?></div><div class="lb">Lecons effectuees</div></div>
    <div class="card"><div class="nb"><?= $total_h ?> h</div><div class="lb">Heures de conduite</div></div>
</div>

<h3>Mes informations</h3>
<table>
    <tr><th>Nom</th><td><?= $ci['prenom_client'].' '.$ci['nom_client'] ?></td></tr>
    <tr><th>Date naissance</th><td><?= date('d/m/Y', strtotime($ci['date_naissance'])) ?></td></tr>
    <tr><th>Adresse</th><td><?= $ci['adresse_client'] ?></td></tr>
    <tr><th>Telephone</th><td><?= $ci['telephone'] ?></td></tr>
    <tr><th>Email</th><td><?= $ci['email'] ?></td></tr>
    <tr><th>Inscription</th><td><?= date('d/m/Y', strtotime($ci['date_inscription'])) ?></td></tr>
    <?php if ($ci['est_etudiant']): ?><tr><th>Etablissement</th><td><?= $ci['nom_etablissement'] ?></td></tr><?php endif; ?>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
