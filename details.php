<?php
 
require_once 'db.php';
 
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
 
if ($id <= 0) {
    header('Location: index.php');
    exit;
}
 
$espace = getEspaceById($id);
 
if (!$espace) {
    header('Location: index.php');
    exit;
}
 
$message_confirm = '';
$erreurs = [];
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $nom_client  = trim(htmlspecialchars($_POST['nom']   ?? ''));
    $email       = trim(htmlspecialchars($_POST['email'] ?? ''));
    $date_resa   = trim($_POST['date']  ?? '');
    $heure_debut = trim($_POST['heure'] ?? '');
    $duree       = (int)($_POST['duree'] ?? 0);
    $message     = trim(htmlspecialchars($_POST['message'] ?? ''));
 
    if (empty($nom_client)) {
        $erreurs[] = 'Votre nom est requis.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'Adresse e-mail invalide ou manquante.';
    }
    if (empty($date_resa)) {
        $erreurs[] = 'Veuillez sélectionner une date.';
    }
    if (empty($heure_debut)) {
        $erreurs[] = 'Veuillez choisir une heure de début.';
    }
    if ($duree < 1 || $duree > 8) {
        $erreurs[] = 'La durée doit être comprise entre 1 et 8 heures.';
    }
 
    if (empty($erreurs)) {
 
        $prix_total = $espace['prix_heure'] * $duree;
 
        // Insertion en base de données
        global $pdo;
        $stmt = $pdo->prepare('
            INSERT INTO reservations (espace_id, nom_client, email, date_resa, heure_debut, duree, prix_total, message)
            VALUES (:espace_id, :nom_client, :email, :date_resa, :heure_debut, :duree, :prix_total, :message)
        ');
        $stmt->execute([
            ':espace_id'  => $espace['id'],
            ':nom_client' => $nom_client,
            ':email'      => $email,
            ':date_resa'  => $date_resa,
            ':heure_debut'=> $heure_debut,
            ':duree'      => $duree,
            ':prix_total' => $prix_total,
            ':message'    => $message ?: null,
        ]);
 
        $message_confirm = sprintf(
            'Merci <strong>%s</strong> ! Votre réservation pour <strong>%s</strong> le <strong>%s à %s</strong> (%dh) est enregistrée. Total : <strong>%.2f €</strong>.',
            $nom_client,
            $espace['nom'],
            date('d/m/Y', strtotime($date_resa)),
            $heure_debut,
            $duree,
            $prix_total
        );
    }
}
 
$page_title = $espace['nom'];
require_once 'header.php';
?>
 
<nav class="breadcrumb">
    <div class="container">
        <a href="index.php">Accueil</a>
        <span class="sep">›</span>
        <a href="index.php">Nos espaces</a>
        <span class="sep">›</span>
        <span><?= htmlspecialchars($espace['nom']) ?></span>
    </div>
</nav>
 
<div class="container detail-layout">
 
    <article class="detail-main">
        <?php if (!empty($espace['image_url'])) : ?>
        <div class="detail-image-wrapper">
            <img
                src="<?= htmlspecialchars($espace['image_url']) ?>"
                alt="Photo de <?= htmlspecialchars($espace['nom']) ?>"
                class="detail-image"
                onerror="this.style.display='none'; this.parentElement.classList.add('img-fallback');"
            >
        </div>
        <?php endif; ?>
 
        <div class="detail-header">
            <h1 class="detail-title"><?= htmlspecialchars($espace['nom']) ?></h1>
            <div class="detail-meta">
                <span class="meta-item">👥 Jusqu'à <?= (int)$espace['capacite'] ?> personnes</span>
                <span class="meta-item meta-price">
                    <?= number_format($espace['prix_heure'], 2, ',', ' ') ?> € <small>/ heure</small>
                </span>
            </div>
        </div>
 
        <div class="detail-description">
            <h2>À propos de cet espace</h2>
            <p><?= nl2br(htmlspecialchars($espace['description'])) ?></p>
        </div>
 
        <div class="detail-equipements">
            <h2>Équipements inclus</h2>
            <?= getEquipements($espace['equipements']) ?>
        </div>
    </article>
 
    <aside class="detail-sidebar">
        <div class="resa-card">
 
            <div class="resa-card-header">
                <h3>Réserver cet espace</h3>
                <p class="resa-price">
                    <strong><?= number_format($espace['prix_heure'], 2, ',', ' ') ?> €</strong> / heure
                </p>
            </div>
 
            <?php if (!empty($message_confirm)) : ?>
                <div class="alert alert-success">
                    ✅ <?= $message_confirm ?>
                </div>
            <?php endif; ?>
 
            <?php if (!empty($erreurs)) : ?>
                <div class="alert alert-error">
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul>
                        <?php foreach ($erreurs as $err) : ?>
                            <li><?= $err ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
 
            <form method="post" action="" class="resa-form">
 
                <input type="hidden" name="espace_id" value="<?= (int)$espace['id'] ?>">
 
                <div class="form-group">
                    <label for="nom">Nom complet *</label>
                    <input type="text" id="nom" name="nom" placeholder="Jean Dupont" required
                        value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                </div>
 
                <div class="form-group">
                    <label for="email">Adresse e-mail *</label>
                    <input type="email" id="email" name="email" placeholder="jean@exemple.fr" required
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
 
                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Date *</label>
                        <input type="date" id="date" name="date" required
                            min="<?= date('Y-m-d') ?>"
                            value="<?= htmlspecialchars($_POST['date'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="heure">Heure de début *</label>
                        <input type="time" id="heure" name="heure" min="08:00" max="19:00" required
                            value="<?= htmlspecialchars($_POST['heure'] ?? '09:00') ?>">
                    </div>
                </div>
 
                <div class="form-group">
                    <label for="duree">Durée (en heures) *</label>
                    <select id="duree" name="duree" required>
                        <?php for ($h = 1; $h <= 8; $h++) : ?>
                            <option value="<?= $h ?>" <?= (($_POST['duree'] ?? 1) == $h) ? 'selected' : '' ?>>
                                <?= $h ?> heure<?= $h > 1 ? 's' : '' ?>
                                — <?= number_format($espace['prix_heure'] * $h, 2, ',', ' ') ?> €
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
 
                <div class="form-group">
                    <label for="message">Message / Précisions (optionnel)</label>
                    <textarea id="message" name="message" rows="3"
                        placeholder="Besoin particulier, nombre de participants prévu..."
                    ><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>
 
                <button type="submit" class="btn-submit">
                    Confirmer la réservation →
                </button>
 
                <p class="resa-notice">* Réservation enregistrée en base de données.</p>
 
            </form>
        </div>
 
        <a href="index.php" class="btn-back">← Voir tous les espaces</a>
    </aside>
 
</div>
 
<?php require_once 'footer.php'; ?>
