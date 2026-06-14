<?php
$page_titre = 'Voitures';
require_once __DIR__ . '/../includes/auth.php';
verifier_admin();
require_once __DIR__ . '/../includes/connexion.php';
$msg = ''; $err = '';

if (isset($_GET['sup'])) {
    try { $pdo->prepare("DELETE FROM VOITURE WHERE id_voiture=?")->execute([$_GET['sup']]); $msg = "Supprime."; }
    catch (Exception $e) { $err = "Impossible."; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->prepare("INSERT INTO VOITURE (immatriculation, km_actuels, date_mise_service, id_modele) VALUES (?,?,?,?)")
            ->execute([$_POST['immatriculation'], $_POST['km_actuels'], $_POST['date_mise_service'], $_POST['id_modele']]);
        $msg = "Voiture ajoutee.";
    } catch (Exception $e) {
        $err = "Immatriculation deja existante.";
    }
}

require_once __DIR__ . '/includes/header.php';
$modeles = $pdo->query("SELECT * FROM MODELE_VOITURE ORDER BY marque, nom_modele")->fetchAll();
?>

<h2>Gestion des voitures</h2>

<h3>Ajouter</h3>
<div class="fb"><form method="POST">
    <div class="fr">
        <div><label>Immatriculation *</label><input name="immatriculation" required></div>
        <div><label>Modele *</label>
            <select name="id_modele" required><option value="">--</option>
            <?php foreach($modeles as $m): ?><option value="<?= $m['id_modele'] ?>"><?= $m['marque'].' '.$m['nom_modele'].' ('.$m['boite_vitesse'].')' ?></option><?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="fr">
        <div><label>Km actuels</label><input type="number" name="km_actuels" value="0"></div>
        <div><label>Mise en service *</label><input type="date" name="date_mise_service" required></div>
    </div>
    <button class="btn bp">Ajouter</button>
</form></div>

<h3>Parc</h3>
<table>
<thead><tr><th>ID</th><th>Immat.</th><th>Modele</th><th>Boite</th><th>Km</th><th>Service</th><th></th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT v.*, mv.marque, mv.nom_modele, mv.boite_vitesse FROM VOITURE v JOIN MODELE_VOITURE mv ON v.id_modele=mv.id_modele ORDER BY mv.marque")->fetchAll() as $v): ?>
<tr>
    <td><?= $v['id_voiture'] ?></td><td><?= $v['immatriculation'] ?></td>
    <td><?= $v['marque'].' '.$v['nom_modele'] ?></td><td><?= $v['boite_vitesse'] ?></td>
    <td><?= number_format($v['km_actuels'],0,',',' ') ?></td>
    <td><?= date('d/m/Y', strtotime($v['date_mise_service'])) ?></td>
    <td><a href="?sup=<?= $v['id_voiture'] ?>" class="btn bd bsm" onclick="return confirm('Supprimer ?')">X</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
