<?php if (!isset($page_titre)) $page_titre = 'Back-office'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_titre ?> - Castellane Auto</title>
<link rel="icon" href="../images/icon.png" type="image/png">
<link rel="stylesheet" href="../style.css">
</head>
<body class="admin-layout">
<header>
    <div><img src="../images/logo.png" alt="Castellane Auto"><h1>Administration</h1><span class="user-info">Connecte : <?= $_SESSION['nom'] ?></span></div>
    <nav>
        <a href="index.php" class="<?= $page_titre=='Tableau de bord'?'active':'' ?>">Accueil</a>
        <a href="clients.php" class="<?= $page_titre=='Clients'?'active':'' ?>">Clients</a>
        <a href="moniteurs.php" class="<?= $page_titre=='Moniteurs'?'active':'' ?>">Moniteurs</a>
        <a href="modeles.php" class="<?= $page_titre=='Modeles'?'active':'' ?>">Modeles</a>
        <a href="voitures.php" class="<?= $page_titre=='Voitures'?'active':'' ?>">Voitures</a>
        <a href="lecons.php" class="<?= $page_titre=='Lecons'?'active':'' ?>">Lecons</a>
        <a href="facturation.php" class="<?= $page_titre=='Facturation'?'active':'' ?>">Facturation</a>
        <a href="planning.php" class="<?= $page_titre=='Planning'?'active':'' ?>">Planning</a>
        <a href="../logout.php" class="logout">Deconnexion</a>
    </nav>
</header>
<main>
<?php if (!empty($msg)): ?><div class="ok"><?= $msg ?></div><?php endif; ?>
<?php if (!empty($err)): ?><div class="ko"><?= $err ?></div><?php endif; ?>
