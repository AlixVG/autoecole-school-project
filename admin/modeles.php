<?php
$page_titre = 'Modeles';
require_once __DIR__ . '/../includes/auth.php';
verifier_admin();
require_once __DIR__ . '/../includes/connexion.php';
$msg = ''; $err = '';

if (isset($_GET['sup'])) {
    try { $pdo->prepare("DELETE FROM MODELE_VOITURE WHERE id_modele=?")->execute([$_GET['sup']]); $msg = "Supprime."; }
    catch (Exception $e) { $err = "Impossible (voitures liees)."; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->prepare("INSERT INTO MODELE_VOITURE (marque, nom_modele, boite_vitesse) VALUES (?,?,?)")
            ->execute([$_POST['marque'], $_POST['nom_modele'], $_POST['boite_vitesse']]);
        $msg = "Modele ajoute.";
    } catch (Exception $e) {
        $err = "Erreur lors de l'ajout du modele.";
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<h2>Gestion des modeles</h2>

<h3>Ajouter</h3>
<div class="fb"><form method="POST">
    <div class="fr">
        <div><label>Marque *</label><input name="marque" required></div>
        <div><label>Nom modele *</label><input name="nom_modele" required></div>
    </div>
    <label>Boite *</label>
    <select name="boite_vitesse" required><option value="manuelle">Manuelle</option><option value="automatique">Automatique</option></select>
    <button class="btn bp">Ajouter</button>
</form></div>

<h3>Liste</h3>
<table>
<thead><tr><th>ID</th><th>Marque</th><th>Modele</th><th>Boite</th><th></th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT * FROM MODELE_VOITURE ORDER BY marque, nom_modele")->fetchAll() as $m): ?>
<tr>
    <td><?= $m['id_modele'] ?></td><td><?= $m['marque'] ?></td><td><?= $m['nom_modele'] ?></td><td><?= $m['boite_vitesse'] ?></td>
    <td><a href="?sup=<?= $m['id_modele'] ?>" class="btn bd bsm" onclick="return confirm('Supprimer ?')">X</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
