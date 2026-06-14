<?php
$page_titre = 'Lecons';
require_once __DIR__ . '/../includes/auth.php';
verifier_admin();
require_once __DIR__ . '/../includes/connexion.php';
$msg = ''; $err = '';

if (isset($_GET['sup'])) {
    $pdo->prepare("DELETE FROM LECON WHERE id_lecon=?")->execute([$_GET['sup']]);
    $msg = "Supprime.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM LECON WHERE date_lecon = ? AND (id_client = ? OR id_moniteur = ? OR id_voiture = ?) AND heure_debut < ADDTIME(?, SEC_TO_TIME(? * 60)) AND ADDTIME(heure_debut, SEC_TO_TIME(duree_minutes * 60)) > ?");
    $stmt->execute([$_POST['date_lecon'], $_POST['id_client'], $_POST['id_moniteur'], $_POST['id_voiture'], $_POST['heure_debut'], $_POST['duree_minutes'], $_POST['heure_debut']]);

    if ($stmt->fetchColumn() > 0) {
        $err = "Conflit ! Eleve, moniteur ou voiture deja occupe sur ce creneau.";
    } else {
        $pdo->prepare("INSERT INTO LECON (date_lecon, heure_debut, duree_minutes, km_parcourus, observation, id_client, id_moniteur, id_voiture) VALUES (?,?,?,?,?,?,?,?)")
            ->execute([$_POST['date_lecon'], $_POST['heure_debut'], $_POST['duree_minutes'], $_POST['km_parcourus'], $_POST['observation'], $_POST['id_client'], $_POST['id_moniteur'], $_POST['id_voiture']]);
        $msg = "Lecon ajoutee.";
    }
}

require_once __DIR__ . '/includes/header.php';
$clients = $pdo->query("SELECT * FROM CLIENT ORDER BY nom_client")->fetchAll();
$moniteurs = $pdo->query("SELECT * FROM MONITEUR ORDER BY nom_moniteur")->fetchAll();
$voitures = $pdo->query("SELECT v.*, mv.marque, mv.nom_modele FROM VOITURE v JOIN MODELE_VOITURE mv ON v.id_modele=mv.id_modele ORDER BY mv.marque")->fetchAll();
?>

<h2>Gestion des lecons</h2>

<h3>Planifier</h3>
<div class="fb"><form method="POST">
    <div class="fr">
        <div><label>Date *</label><input type="date" name="date_lecon" required></div>
        <div><label>Heure *</label><input type="time" name="heure_debut" required></div>
    </div>
    <div class="fr">
        <div><label>Duree (min) *</label>
            <select name="duree_minutes"><option value="60">60</option><option value="90">90</option><option value="120">120</option></select>
        </div>
        <div><label>Km</label><input type="number" name="km_parcourus" value="0"></div>
    </div>
    <label>Client *</label>
    <select name="id_client" required><option value="">--</option>
    <?php foreach($clients as $c): ?><option value="<?= $c['id_client'] ?>"><?= $c['nom_client'].' '.$c['prenom_client'] ?></option><?php endforeach; ?></select>
    <label>Moniteur *</label>
    <select name="id_moniteur" required><option value="">--</option>
    <?php foreach($moniteurs as $m): ?><option value="<?= $m['id_moniteur'] ?>"><?= $m['nom_moniteur'].' '.$m['prenom_moniteur'] ?></option><?php endforeach; ?></select>
    <label>Voiture *</label>
    <select name="id_voiture" required><option value="">--</option>
    <?php foreach($voitures as $v): ?><option value="<?= $v['id_voiture'] ?>"><?= $v['immatriculation'].' - '.$v['marque'].' '.$v['nom_modele'] ?></option><?php endforeach; ?></select>
    <label>Observation</label><textarea name="observation"></textarea>
    <button class="btn bp">Planifier</button>
</form></div>

<h3>Historique</h3>
<table>
<thead><tr><th>Date</th><th>Heure</th><th>Duree</th><th>Client</th><th>Moniteur</th><th>Voiture</th><th>Km</th><th></th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT l.*, c.nom_client, c.prenom_client, m.nom_moniteur, m.prenom_moniteur, v.immatriculation FROM LECON l JOIN CLIENT c ON l.id_client=c.id_client JOIN MONITEUR m ON l.id_moniteur=m.id_moniteur JOIN VOITURE v ON l.id_voiture=v.id_voiture ORDER BY l.date_lecon DESC, l.heure_debut DESC")->fetchAll() as $l): ?>
<tr>
    <td><?= date('d/m/Y', strtotime($l['date_lecon'])) ?></td>
    <td><?= substr($l['heure_debut'],0,5) ?></td><td><?= $l['duree_minutes'] ?> min</td>
    <td><?= $l['prenom_client'].' '.$l['nom_client'] ?></td>
    <td><?= $l['prenom_moniteur'].' '.$l['nom_moniteur'] ?></td>
    <td><?= $l['immatriculation'] ?></td><td><?= $l['km_parcourus'] ?></td>
    <td><a href="?sup=<?= $l['id_lecon'] ?>" class="btn bd bsm" onclick="return confirm('Supprimer ?')">X</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
