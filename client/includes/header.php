<?php if (!isset($page_titre)) $page_titre = 'Espace client'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $page_titre ?> - Castellane Auto</title>
<link rel="icon" href="../images/icon.png" type="image/png">
<link rel="stylesheet" href="../style.css">
</head>
<body class="client-layout">
<header>
    <div><img src="../images/logo.png" alt="Castellane Auto"><h1>Espace client</h1><span class="user-info">Bienvenue <?= $_SESSION['nom'] ?></span></div>
    <nav>
        <a href="index.php" class="<?= $page_titre=='Mon espace'?'active':'' ?>">Mon espace</a>
        <a href="mes_lecons.php" class="<?= $page_titre=='Mes lecons'?'active':'' ?>">Mes lecons</a>
        <a href="mes_factures.php" class="<?= $page_titre=='Mes factures'?'active':'' ?>">Mes factures</a>
        <a href="../logout.php" class="logout">Deconnexion</a>
    </nav>
</header>
<main>
