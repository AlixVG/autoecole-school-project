<?php
$page_titre = 'Clients';
require_once __DIR__ . '/../includes/auth.php';
verifier_admin();
require_once __DIR__ . '/../includes/connexion.php';
$msg = ''; $err = '';

if (isset($_GET['sup'])) {
    try { $pdo->prepare("DELETE FROM CLIENT WHERE id_client=?")->execute([$_GET['sup']]); $msg = "Client supprime."; }
    catch (Exception $e) { $err = "Impossible (donnees liees)."; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $est_etudiant = isset($_POST['est_etudiant']) ? 1 : 0;
        $id_etab = ($est_etudiant && !empty($_POST['id_etablissement'])) ? $_POST['id_etablissement'] : null;
        $pdo->prepare("INSERT INTO CLIENT (nom_client, prenom_client, date_naissance, adresse_client, telephone, email, date_inscription, date_prevue_code, date_prevue_permis, est_etudiant, id_etablissement) VALUES (?,?,?,?,?,?,?,?,?,?,?)")
            ->execute([$_POST['nom'], $_POST['prenom'], $_POST['date_naissance'], $_POST['adresse'], $_POST['telephone'], $_POST['email'], $_POST['date_inscription'], $_POST['date_prevue_code'] ?: null, $_POST['date_prevue_permis'] ?: null, $est_etudiant, $id_etab]);
        $msg = "Client ajoute. Connexion : email + nom de famille.";
    } catch (Exception $e) {
        $err = "Erreur lors de l'ajout du client.";
    }
}

require_once __DIR__ . '/includes/header.php';
$etabs = $pdo->query("SELECT * FROM ETABLISSEMENT ORDER BY nom_etablissement")->fetchAll();
?>

<h2>Gestion des clients</h2>

<h3>Ajouter un client</h3>
<div class="fb"><form method="POST">
    <div class="fr">
        <div><label>Nom *</label><input name="nom" required></div>
        <div><label>Prenom *</label><input name="prenom" required></div>
    </div>
    <div class="fr">
        <div><label>Date naissance *</label><input type="date" name="date_naissance" required></div>
        <div><label>Date inscription *</label><input type="date" name="date_inscription" value="<?= date('Y-m-d') ?>" required></div>
    </div>
    <label>Adresse</label><input name="adresse">
    <div class="fr">
        <div><label>Telephone</label><input name="telephone"></div>
        <div><label>Email</label><input type="email" name="email"></div>
    </div>
    <div class="fr">
        <div><label>Date prevue code</label><input type="date" name="date_prevue_code"></div>
        <div><label>Date prevue permis</label><input type="date" name="date_prevue_permis"></div>
    </div>
    <div class="fr">
        <div><label><input type="checkbox" name="est_etudiant" value="1"> Etudiant</label></div>
        <div><label>Etablissement</label>
            <select name="id_etablissement"><option value="">--</option>
            <?php foreach($etabs as $e): ?><option value="<?= $e['id_etablissement'] ?>"><?= $e['nom_etablissement'] ?></option><?php endforeach; ?>
            </select>
        </div>
    </div>
    <button class="btn bp">Ajouter</button>
</form></div>

<h3>Liste des clients</h3>
<table>
<thead><tr><th>ID</th><th>Nom</th><th>Prenom</th><th>Naissance</th><th>Tel</th><th>Email</th><th>Code prevu</th><th>Permis prevu</th><th>Etudiant</th><th></th></tr></thead>
<tbody>
<?php foreach ($pdo->query("SELECT c.*, e.nom_etablissement FROM CLIENT c LEFT JOIN ETABLISSEMENT e ON c.id_etablissement=e.id_etablissement ORDER BY c.nom_client")->fetchAll() as $c): ?>
<tr>
    <td><?= $c['id_client'] ?></td>
    <td><?= $c['nom_client'] ?></td>
    <td><?= $c['prenom_client'] ?></td>
    <td><?= date('d/m/Y', strtotime($c['date_naissance'])) ?></td>
    <td><?= $c['telephone'] ?></td>
    <td><?= $c['email'] ?: '-' ?></td>
    <td><?= $c['date_prevue_code'] ? date('d/m/Y', strtotime($c['date_prevue_code'])) : '-' ?></td>
    <td><?= $c['date_prevue_permis'] ? date('d/m/Y', strtotime($c['date_prevue_permis'])) : '-' ?></td>
    <td><?= $c['est_etudiant'] ? 'Oui - '.$c['nom_etablissement'] : '-' ?></td>
    <td><a href="?sup=<?= $c['id_client'] ?>" class="btn bd bsm" onclick="return confirm('Supprimer ?')">X</a></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
