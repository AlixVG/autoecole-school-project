<?php
$page_titre = 'Moniteurs';
require_once __DIR__ . '/../includes/auth.php';
verifier_admin();
require_once __DIR__ . '/../includes/connexion.php';
$msg = ''; $err = '';

if (isset($_GET['sup'])) {
    try { $pdo->prepare("DELETE FROM MONITEUR WHERE id_moniteur=?")->execute([$_GET['sup']]); $msg = "Supprime."; }
    catch (Exception $e) { $err = "Impossible."; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->prepare("INSERT INTO MONITEUR (nom_moniteur, prenom_moniteur, telephone, email, date_embauche) VALUES (?,?,?,?,?)")
            ->execute([$_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['email'], $_POST['date_embauche']]);
        $msg = "Moniteur ajoute.";
    } catch (Exception $e) {
        $err = "Erreur lors de l'ajout du moniteur.";
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<h2>Gestion des moniteurs</h2>

<h3>Ajouter</h3>
<div class="fb"><form method="POST">
    <div class="fr">
        <div><label>Nom *</label><input name="nom" required></div>
        <div><label>Prenom *</label><input name="prenom" required></div>
    </div>
    <div class="fr">
        <div><label>Telephone</label><input name="telephone"></div>
        <div><label>Email</label><input type="email" name="email"></div>
    </div>
    <label>Date embauche *</label><input type="date" name="date_embauche" required>
    <button class="btn bp">Ajouter</button>
</form></div>

<h3>Liste</h3>
<table>
<thead><tr><th>ID</th><th>Nom</th><th>Prenom</th><th>Tel</th><th>Email</th><th>Embauche</th><th>Lecons</th><th></th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT m.*, COUNT(l.id_lecon) as nb FROM MONITEUR m LEFT JOIN LECON l ON m.id_moniteur=l.id_moniteur GROUP BY m.id_moniteur ORDER BY m.nom_moniteur")->fetchAll() as $m): ?>
<tr>
    <td><?= $m['id_moniteur'] ?></td><td><?= $m['nom_moniteur'] ?></td><td><?= $m['prenom_moniteur'] ?></td>
    <td><?= $m['telephone'] ?></td><td><?= $m['email'] ?></td>
    <td><?= date('d/m/Y', strtotime($m['date_embauche'])) ?></td><td><?= $m['nb'] ?></td>
    <td><a href="?sup=<?= $m['id_moniteur'] ?>" class="btn bd bsm" onclick="return confirm('Supprimer ?')">X</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
