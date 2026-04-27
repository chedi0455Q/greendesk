<?php
require_once 'db.php';
$espaces = getEspaces();
$page_title = 'Nos Espaces';
require_once 'header.php';
?>
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
<section class="espaces-section" id="espaces">
    <div class="container">
        <div class="section-header">
            <h2>Nos espaces de travail</h2>
            <p>Des prix clairs, à la réservation à l'heure.</p>
        </div>
        <?php if (!empty($espaces)) : ?>

            <div class="espaces-grid">

                <?php foreach ($espaces as $espace) : ?>
                <article class="espace-card">
                    <div class="card-image">
                        <?php if (!empty($espace['image_url'])) : ?>
                            <img
                                src="<?= htmlspecialchars($espace['image_url']) ?>"
                                alt="Photo de <?= htmlspecialchars($espace['nom']) ?>"
                                loading="lazy"
                                onerror="this.style.display='none'"
                            >
                        <?php endif; ?>
                        <div class="card-capacity-badge">
                            👥 <?= (int)$espace['capacite'] ?> pers. max
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title"><?= htmlspecialchars($espace['nom']) ?></h3>
                        <p class="card-desc">
                            <?= htmlspecialchars(mb_strimwidth($espace['description'], 0, 110, '…')) ?>
                        </p>
                        <?= getEquipements($espace['equipements']) ?>
                        <div class="card-footer">
                            <div class="card-price">
                                <span class="price-amount"><?= number_format($espace['prix_heure'], 2, ',', ' ') ?> €</span>
                                <span class="price-unit">/ heure</span>
                            </div>
                            <a href="details.php?id=<?= (int)$espace['id'] ?>" class="btn-card">
                                Réserver →
                            </a>
                        </div>
                    </div>

                </article>
                <?php endforeach; ?>

            </div>

        <?php else : ?>
            <p class="no-results">Aucun espace disponible pour le moment. Revenez bientôt !</p>
        <?php endif; ?>
    </div>
</section>
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
require_once 'footer.php';
?>
