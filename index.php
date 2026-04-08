<?php
// ============================================================
//  index.php — Page d'accueil : liste de tous les espaces
// ============================================================

// 1. Inclusion de la connexion BDD et des fonctions (db.php)
require_once 'db.php';

// 2. Récupération des données AVANT d'afficher quoi que ce soit
//    C'est une bonne pratique : on prépare les données en haut, on affiche en bas
$espaces = getEspaces();

// 3. Titre de la page (utilisé dans le <title> du header)
$page_title = 'Nos Espaces';

// 4. On inclut le header HTML (ouverture de la page)
require_once 'header.php';
?>

<!-- ===== SECTION HERO (bannière d'accueil) ===== -->
<section class="hero">
    <div class="container hero-inner">
        <div class="hero-text">
            <span class="hero-label">Coworking à Paris</span>
            <h1>Trouvez l'espace<br>qui vous <em>ressource</em>.</h1>
            <p>Green Desk vous propose des lieux de travail inspirants, au cœur de Paris. Réservez à l'heure, en toute simplicité.</p>
            <a href="#espaces" class="btn-primary">Voir les espaces ↓</a>
        </div>
        <div class="hero-badge">
            <div class="badge-circle">
                <span>4</span>
                <small>espaces<br>disponibles</small>
            </div>
        </div>
    </div>
</section>

<!-- ===== SECTION PRINCIPALE : LISTE DES ESPACES ===== -->
<section class="espaces-section" id="espaces">
    <div class="container">

        <!-- En-tête de la section -->
        <div class="section-header">
            <h2>Nos espaces de travail</h2>
            <p>Des prix clairs, à la réservation à l'heure.</p>
        </div>

        <!-- Grille des espaces -->
        <!-- On vérifie qu'il y a bien des données avant d'afficher -->
        <?php if (!empty($espaces)) : ?>

            <div class="espaces-grid">

                <?php foreach ($espaces as $espace) : ?>
                <!-- Chaque espace = une carte -->
                <article class="espace-card">

                    <!-- Image de l'espace -->
                    <div class="card-image">
                        <?php if (!empty($espace['image_url'])) : ?>
                            <img
                                src="<?= htmlspecialchars($espace['image_url']) ?>"
                                alt="Photo de <?= htmlspecialchars($espace['nom']) ?>"
                                loading="lazy"
                                onerror="this.style.display='none'"
                            >
                        <?php endif; ?>
                        <!-- Badge de capacité positionné sur l'image -->
                        <div class="card-capacity-badge">
                            👥 <?= (int)$espace['capacite'] ?> pers. max
                        </div>
                    </div>

                    <!-- Corps de la carte -->
                    <div class="card-body">
                        <h3 class="card-title"><?= htmlspecialchars($espace['nom']) ?></h3>

                        <!-- Description tronquée pour ne pas alourdir la liste -->
                        <p class="card-desc">
                            <?= htmlspecialchars(mb_strimwidth($espace['description'], 0, 110, '…')) ?>
                        </p>

                        <!-- Équipements sous forme de badges (via notre fonction db.php) -->
                        <?= getEquipements($espace['equipements']) ?>

                        <!-- Pied de carte : prix + lien -->
                        <div class="card-footer">
                            <div class="card-price">
                                <span class="price-amount"><?= number_format($espace['prix_heure'], 2, ',', ' ') ?> €</span>
                                <span class="price-unit">/ heure</span>
                            </div>
                            <!-- Lien vers la page de détail, on passe l'id dans l'URL -->
                            <a href="details.php?id=<?= (int)$espace['id'] ?>" class="btn-card">
                                Réserver →
                            </a>
                        </div>
                    </div>

                </article>
                <?php endforeach; ?>

            </div><!-- /.espaces-grid -->

        <?php else : ?>
            <!-- Message affiché si la table est vide -->
            <p class="no-results">Aucun espace disponible pour le moment. Revenez bientôt !</p>
        <?php endif; ?>

    </div><!-- /.container -->
</section>

<!-- ===== SECTION AVANTAGES ===== -->
<section class="avantages-section">
    <div class="container avantages-grid">

        <div class="avantage-item">
            <span class="avantage-icon">⚡</span>
            <h4>Réservation instantanée</h4>
            <p>Confirmez votre créneau en moins de 2 minutes.</p>
        </div>

        <div class="avantage-item">
            <span class="avantage-icon">🌱</span>
            <h4>Espaces éco-responsables</h4>
            <p>Énergie verte, plantes, matériaux naturels.</p>
        </div>

        <div class="avantage-item">
            <span class="avantage-icon">🔒</span>
            <h4>Sans engagement</h4>
            <p>Payez uniquement ce que vous consommez.</p>
        </div>

    </div>
</section>

<?php
// 5. On inclut le footer HTML (fermeture de la page)
require_once 'footer.php';
?>
