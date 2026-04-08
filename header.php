<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <!-- Responsive : indispensable pour mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- La variable $page_title doit être définie AVANT d'inclure ce header -->
    <title><?= htmlspecialchars($page_title ?? 'Green Desk') ?> — Green Desk</title>

    <!-- Google Fonts : Playfair Display (élégant) + DM Sans (lisible) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Feuille de styles principale -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ===== EN-TÊTE DU SITE ===== -->
<header class="site-header">
    <div class="container header-inner">

        <!-- Logo textuel avec icône feuille -->
        <a href="index.php" class="logo">
            <span class="logo-icon">🌿</span>
            <span class="logo-text">Green<strong>Desk</strong></span>
        </a>

        <!-- Navigation principale -->
        <nav class="main-nav">
            <a href="index.php">Nos espaces</a>
            <a href="#contact" class="btn-nav">Nous contacter</a>
        </nav>

    </div>
</header>

<!-- ===== CONTENU PRINCIPAL (ouverture du tag) ===== -->
<main class="site-main">
