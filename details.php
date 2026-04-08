<?php
// ============================================================
//  details.php — Détail d'un espace + formulaire de réservation
// ============================================================

require_once 'db.php';

// --- 1. Récupération et validation de l'identifiant dans l'URL ---
// $_GET['id'] contient la valeur passée après ?id= dans l'URL
// (int) force la conversion en entier → protection basique contre les injections

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Si l'id est invalide (0 ou négatif), on redirige vers l'accueil
if ($id <= 0) {
    header('Location: index.php');
    exit; // Toujours appeler exit() après header() !
}

// --- 2. Requête en base de données ---
$espace = getEspaceById($id);

// Si l'espace n'existe pas en BDD, on redirige aussi
if (!$espace) {
    header('Location: index.php');
    exit;
}

// --- 3. Gestion du formulaire de réservation ---
$message_confirm = ''; // Message de confirmation à afficher
$erreurs = [];         // Tableau d'erreurs de validation

// On vérifie si le formulaire a été soumis (méthode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // --- Récupération et nettoyage des données du formulaire ---
    // trim() : supprime les espaces en début/fin de chaîne
    // htmlspecialchars() : convertit les caractères spéciaux (sécurité XSS)
    $nom_client  = trim(htmlspecialchars($_POST['nom']   ?? ''));
    $email       = trim(htmlspecialchars($_POST['email'] ?? ''));
    $date_resa   = trim($_POST['date']  ?? '');
    $heure_debut = trim($_POST['heure'] ?? '');
    $duree       = (int)($_POST['duree'] ?? 0);
    $message     = trim(htmlspecialchars($_POST['message'] ?? ''));

    // --- Validation des champs ---
    if (empty($nom_client)) {
        $erreurs[] = 'Votre nom est requis.';
    }
    // filter_var : fonction PHP native pour valider un email
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

    // --- Si aucune erreur : traitement de la réservation ---
    if (empty($erreurs)) {
        // Calcul du prix total : prix_heure × durée
        $prix_total = $espace['prix_heure'] * $duree;

        // En situation réelle, on ferait : INSERT INTO reservations (...)
        // Ici c'est simulé : on affiche juste un message de confirmation
        $message_confirm = sprintf(
            'Merci <strong>%s</strong> ! Votre réservation pour <strong>%s</strong> le <strong>%s à %s</strong> (%dh) est enregistrée. Total : <strong>%.2f €</strong>.',
            $nom_client,
            $espace['nom'],
            date('d/m/Y', strtotime($date_resa)), // Formatage de la date
            $heure_debut,
            $duree,
            $prix_total
        );
    }
}

// --- 4. Titre de la page ---
$page_title = $espace['nom'];

require_once 'header.php';
?>

<!-- ===== FIL D'ARIANE (breadcrumb) ===== -->
<nav class="breadcrumb">
    <div class="container">
        <a href="index.php">Accueil</a>
        <span class="sep">›</span>
        <a href="index.php">Nos espaces</a>
        <span class="sep">›</span>
        <span><?= htmlspecialchars($espace['nom']) ?></span>
    </div>
</nav>

<!-- ===== PAGE DÉTAIL ===== -->
<div class="container detail-layout">

    <!-- ===== COLONNE GAUCHE : Infos de l'espace ===== -->
    <article class="detail-main">

        <!-- Image principale -->
        <?php if (!empty($espace['image_url'])) : ?>
        <div class="detail-image-wrapper">
            <img
                src="<?= htmlspecialchars($espace['image_url']) ?>"
                alt="Photo de <?= htmlspecialchars($espace['nom']) ?>"
                class="detail-image"
                onerror="this.parentElement.style.display='none'"
            >
        </div>
        <?php endif; ?>

        <!-- Nom et méta-infos -->
        <div class="detail-header">
            <h1 class="detail-title"><?= htmlspecialchars($espace['nom']) ?></h1>
            <div class="detail-meta">
                <span class="meta-item">👥 Jusqu'à <?= (int)$espace['capacite'] ?> personnes</span>
                <span class="meta-item meta-price">
                    <?= number_format($espace['prix_heure'], 2, ',', ' ') ?> € <small>/ heure</small>
                </span>
            </div>
        </div>

        <!-- Description complète -->
        <div class="detail-description">
            <h2>À propos de cet espace</h2>
            <!-- nl2br() : convertit les retours à la ligne \n en <br> HTML -->
            <p><?= nl2br(htmlspecialchars($espace['description'])) ?></p>
        </div>

        <!-- Équipements complets -->
        <div class="detail-equipements">
            <h2>Équipements inclus</h2>
            <?= getEquipements($espace['equipements']) ?>
        </div>

    </article>

    <!-- ===== COLONNE DROITE : Formulaire de réservation ===== -->
    <aside class="detail-sidebar">
        <div class="resa-card">

            <div class="resa-card-header">
                <h3>Réserver cet espace</h3>
                <p class="resa-price">
                    <strong><?= number_format($espace['prix_heure'], 2, ',', ' ') ?> €</strong> / heure
                </p>
            </div>

            <!-- Message de confirmation (affiché après soumission réussie) -->
            <?php if (!empty($message_confirm)) : ?>
                <div class="alert alert-success">
                    ✅ <?= $message_confirm ?>
                </div>
            <?php endif; ?>

            <!-- Affichage des erreurs de validation -->
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

            <!-- LE FORMULAIRE -->
            <!-- method="post" : les données sont envoyées dans le corps de la requête HTTP -->
            <!-- action="" : on soumet sur la même page (details.php?id=X) -->
            <form method="post" action="" class="resa-form">

                <!-- Champ caché : on passe l'id de l'espace pour savoir ce qu'on réserve -->
                <input type="hidden" name="espace_id" value="<?= (int)$espace['id'] ?>">

                <div class="form-group">
                    <label for="nom">Nom complet *</label>
                    <input
                        type="text"
                        id="nom"
                        name="nom"
                        placeholder="Jean Dupont"
                        required
                        value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="email">Adresse e-mail *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="jean@exemple.fr"
                        required
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    >
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Date *</label>
                        <!-- min : empêche de réserver dans le passé -->
                        <input
                            type="date"
                            id="date"
                            name="date"
                            required
                            min="<?= date('Y-m-d') ?>"
                            value="<?= htmlspecialchars($_POST['date'] ?? '') ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="heure">Heure de début *</label>
                        <input
                            type="time"
                            id="heure"
                            name="heure"
                            min="08:00"
                            max="19:00"
                            required
                            value="<?= htmlspecialchars($_POST['heure'] ?? '09:00') ?>"
                        >
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
                    <textarea
                        id="message"
                        name="message"
                        rows="3"
                        placeholder="Besoin particulier, nombre de participants prévu..."
                    ><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    Confirmer la réservation →
                </button>

                <p class="resa-notice">* Réservation simulée — aucun paiement requis.</p>

            </form>

        </div><!-- /.resa-card -->

        <!-- Lien retour -->
        <a href="index.php" class="btn-back">← Voir tous les espaces</a>

    </aside>

</div><!-- /.detail-layout -->

<?php require_once 'footer.php'; ?>
