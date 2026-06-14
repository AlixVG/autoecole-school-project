<?php
$page_titre = 'Mes lecons';
require_once __DIR__ . '/../includes/auth.php';
verifier_client();
require_once __DIR__ . '/../includes/connexion.php';
require_once __DIR__ . '/includes/header.php';

$id = $_SESSION['id_client'];
?>

<h2>Mes lecons</h2>

<table>
<thead><tr><th>Date</th><th>Heure</th><th>Duree</th><th>Moniteur</th><th>Voiture</th><th>Km</th><th>Observation</th></tr></thead>
<tbody>
<?php
$st = $pdo->prepare("SELECT l.*, m.nom_moniteur, m.prenom_moniteur, v.immatriculation, mv.marque, mv.nom_modele FROM LECON l JOIN MONITEUR m ON l.id_moniteur=m.id_moniteur JOIN VOITURE v ON l.id_voiture=v.id_voiture JOIN MODELE_VOITURE mv ON v.id_modele=mv.id_modele WHERE l.id_client=? ORDER BY l.date_lecon DESC, l.heure_debut DESC");
$st->execute([$id]);
foreach ($st->fetchAll() as $l): ?>
<tr>
    <td><?= date('d/m/Y', strtotime($l['date_lecon'])) ?></td>
    <td><?= substr($l['heure_debut'],0,5) ?></td>
    <td><?= $l['duree_minutes'] ?> min</td>
    <td><?= $l['prenom_moniteur'].' '.$l['nom_moniteur'] ?></td>
    <td><?= $l['immatriculation'].' ('.$l['marque'].' '.$l['nom_modele'].')' ?></td>
    <td><?= $l['km_parcourus'] ?></td>
    <td><?= $l['observation'] ?? '' ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
