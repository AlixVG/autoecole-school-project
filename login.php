<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/connexion.php';

$err = '';

if (est_admin()) { header('Location: admin/index.php'); exit; }
if (est_client()) { header('Location: client/index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'];
    $mdp = $_POST['mdp'];

    if ($identifiant === 'admin' && $mdp === 'admin') {
        $_SESSION['role'] = 'admin';
        $_SESSION['nom'] = 'Administrateur';
        header('Location: admin/index.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM CLIENT WHERE email = ?");
    $stmt->execute([$identifiant]);
    $client = $stmt->fetch();

    if ($client && strtolower($client['nom_client']) === strtolower($mdp)) {
        $_SESSION['role'] = 'client';
        $_SESSION['nom'] = $client['prenom_client'] . ' ' . $client['nom_client'];
        $_SESSION['id_client'] = $client['id_client'];
        header('Location: client/index.php');
        exit;
    }

    $err = "Identifiants incorrects.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion - Castellane Auto</title>
<link rel="icon" href="images/icon.png" type="image/png">
<link rel="stylesheet" href="style.css">
</head>
<body class="login-layout">
<div class="login-box">
    <h2>Connexion</h2>
    <?php if ($err): ?><div class="err"><?= $err ?></div><?php endif; ?>
    <form method="POST">
        <label>Identifiant</label>
        <input type="text" name="identifiant" placeholder="Email client ou 'admin'" required>
        <label>Mot de passe</label>
        <input type="password" name="mdp" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
    <p class="info">
        Admin : admin / admin<br>
        Client : email / nom de famille
    </p>
</div>
</body>
</html>
