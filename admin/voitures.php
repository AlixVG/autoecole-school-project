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
    if ($_POST['action'] === 'ajout') {
        try {
            $pdo->prepare("INSERT INTO VOITURE (immatriculation, km_actuels, date_mise_service, id_modele, disponible) VALUES (?,?,?,?,?)")
                ->execute([$_POST['immatriculation'], $_POST['km_actuels'], $_POST['date_mise_service'], $_POST['id_modele'], (isset($_POST['disponible']) ? 1 : 0)]);
            $msg = "Voiture ajoutee.";
        } catch (Exception $e) {
            $err = "Immatriculation deja existante.";
        }
    } else if ($_POST['action'] === 'maj_km') {
        try {
            $stmt = $pdo->prepare("SELECT km_actuels FROM VOITURE WHERE id_voiture=?");
            $stmt->execute([$_POST['id_voiture']]);
            $km_actuel = $stmt->fetchColumn();

            if ($_POST['km_actuels'] < $km_actuel) {
                $err = "Le kilométrage ne peut pas diminuer.";
            } else {
                $pdo->prepare("UPDATE VOITURE SET km_actuels = ? WHERE id_voiture = ?")
                ->execute([$_POST['km_actuels'], $_POST['id_voiture']]);
                $msg = "Kilométrage mis à jour";
            }
        } catch (Exception $e) {
            $err = "Erreur lors de la mise a jour";
        }
    }
}

require_once __DIR__ . '/includes/header.php';
$modeles = $pdo->query("SELECT * FROM MODELE_VOITURE ORDER BY marque, nom_modele")->fetchAll();
?>

<h2>Gestion des voitures</h2>

<h3>Ajouter</h3>
<div class="fb"><form method="POST">
    <input type="hidden" name="action" value="ajout">
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
    <div><label><input type="checkbox" name="disponible" value="1">Disponible</label></div><br>
    <button class="btn bp">Ajouter</button>
</form></div>

<h3>Parc</h3>
<table>
<thead><tr><th>ID</th><th>Immat.</th><th>Modele</th><th>Boite</th><th>Km</th><th>Service</th><th>Disponibilité</th><th></th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT v.*, mv.marque, mv.nom_modele, mv.boite_vitesse FROM VOITURE v JOIN MODELE_VOITURE mv ON v.id_modele=mv.id_modele ORDER BY mv.marque")->fetchAll() as $v): ?>
<tr>
    <td><?= $v['id_voiture'] ?></td><td><?= $v['immatriculation'] ?></td>
    <td><?= $v['marque'].' '.$v['nom_modele'] ?></td><td><?= $v['boite_vitesse'] ?></td>
    <td>
        <form method="POST" style="display:flex; gap:4px">
            <input type="hidden" name="action" value="maj_km">
            <input type="hidden" name="id_voiture" value="<?= $v['id_voiture'] ?>">
            <input type="number" name="km_actuels" value="<?= $v['km_actuels'] ?>" style="width:90px;">
            <button class="btn bp bsm">OK</button>
        </form>
    </td>
    <td><?= date('d/m/Y', strtotime($v['date_mise_service'])) ?></td>
    <td><?= $v['disponible'] ? '<span class="text-success">Disponible</span>' : '<span class="text-danger">Indisponible</span>' ?></td>
    <td><a href="?sup=<?= $v['id_voiture'] ?>" class="btn bd bsm" onclick="return confirm('Supprimer ?')">X</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
