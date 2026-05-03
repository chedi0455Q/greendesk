<?php
require_once 'db.php';
 
$page_title = 'Administration — Réservations';
 

if (isset($_GET['supprimer']) && (int)$_GET['supprimer'] > 0) {
    $stmt = $pdo->prepare('DELETE FROM reservations WHERE id = :id');
    $stmt->execute([':id' => (int)$_GET['supprimer']]);
    header('Location: admin.php?deleted=1');
    exit;
}
 
$stmt = $pdo->query('
    SELECT r.*, e.nom AS espace_nom, e.prix_heure
    FROM reservations r
    JOIN espaces e ON r.espace_id = e.id
    ORDER BY r.date_resa DESC, r.heure_debut DESC
');
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Réservations | Green Desk</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-wrapper {
            max-width: 1100px;
            margin: 2.5rem auto;
            padding: 0 1.5rem 4rem;
        }
        .admin-title {
            font-family: var(--font-titre);
            font-size: 2rem;
            color: var(--vert-sapin);
            margin-bottom: 0.4rem;
        }
        .admin-subtitle {
            color: var(--gris-moyen);
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        .stats-bar {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .stat-box {
            background: var(--blanc-pur);
            border-radius: var(--radius);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow-card);
            flex: 1;
            min-width: 150px;
            text-align: center;
        }
        .stat-box .stat-num {
            font-family: var(--font-titre);
            font-size: 2rem;
            color: var(--vert-sapin);
            display: block;
        }
        .stat-box .stat-label {
            font-size: 0.8rem;
            color: var(--gris-moyen);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .admin-table-wrapper {
            background: var(--blanc-pur);
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        thead {
            background: var(--vert-sapin);
            color: var(--blanc-pur);
        }
        thead th {
            padding: 1rem 1.2rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        tbody tr {
            border-bottom: 1px solid var(--gris-pale);
            transition: background var(--transition);
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--vert-pale); }
        tbody td {
            padding: 0.9rem 1.2rem;
            color: var(--gris-anthracite);
            vertical-align: middle;
        }
        .badge-espace {
            background: var(--vert-pale);
            color: var(--vert-sapin);
            padding: 0.2rem 0.7rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .prix-cell {
            font-weight: 700;
            color: var(--vert-sapin);
        }
        .btn-supprimer {
            background: none;
            border: 1.5px solid #e74c3c;
            color: #e74c3c;
            border-radius: var(--radius-sm);
            padding: 0.3rem 0.8rem;
            font-size: 0.82rem;
            cursor: pointer;
            transition: all var(--transition);
            text-decoration: none;
        }
        .btn-supprimer:hover {
            background: #e74c3c;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--gris-moyen);
            font-size: 1rem;
        }
        .empty-state span { font-size: 3rem; display: block; margin-bottom: 1rem; }
        .alert-success-top {
            background: #D4EDDA;
            color: #155724;
            border: 1px solid #C3E6CB;
            border-radius: var(--radius-sm);
            padding: 0.8rem 1.2rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .btn-retour {
            display: inline-block;
            margin-bottom: 1.5rem;
            color: var(--vert-sapin);
            font-weight: 500;
            font-size: 0.9rem;
        }
        .btn-retour:hover { color: var(--vert-moyen); }
    </style>
</head>
<body>
 
<?php
// Header simplifié
?>
<header class="site-header">
    <div class="container header-inner">
        <a href="index.php" class="logo">
            <span class="logo-icon">🌿</span>
            <span class="logo-text">Green<strong>Desk</strong></span>
        </a>
        <nav class="main-nav">
            <a href="index.php">← Retour au site</a>
        </nav>
    </div>
</header>
 
<div class="admin-wrapper">
 
    <a href="index.php" class="btn-retour">← Retour au site</a>
 
    <h1 class="admin-title">🗂️ Réservations</h1>
    <p class="admin-subtitle">Liste de toutes les réservations enregistrées.</p>
 
    <?php if (isset($_GET['deleted'])) : ?>
        <div class="alert-success-top">✅ Réservation supprimée avec succès.</div>
    <?php endif; ?>
 
    <?php
    // Calcul des stats
    $total_resa = count($reservations);
    $total_ca = array_sum(array_column($reservations, 'prix_total'));
    $espaces_uniques = count(array_unique(array_column($reservations, 'espace_id')));
    ?>
 
    <div class="stats-bar">
        <div class="stat-box">
            <span class="stat-num"><?= $total_resa ?></span>
            <span class="stat-label">Réservations</span>
        </div>
        <div class="stat-box">
            <span class="stat-num"><?= number_format($total_ca, 2, ',', ' ') ?> €</span>
            <span class="stat-label">Chiffre d'affaires</span>
        </div>
        <div class="stat-box">
            <span class="stat-num"><?= $espaces_uniques ?></span>
            <span class="stat-label">Espaces réservés</span>
        </div>
    </div>
 
    <div class="admin-table-wrapper">
        <?php if (empty($reservations)) : ?>
            <div class="empty-state">
                <span>📭</span>
                Aucune réservation pour le moment.
            </div>
        <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Espace</th>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Durée</th>
                    <th>Total</th>
                    <th>Réservé le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r) : ?>
                <tr>
                    <td><?= (int)$r['id'] ?></td>
                    <td><span class="badge-espace"><?= htmlspecialchars($r['espace_nom']) ?></span></td>
                    <td><?= htmlspecialchars($r['nom_client']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['date_resa'])) ?></td>
                    <td><?= htmlspecialchars($r['heure_debut']) ?></td>
                    <td><?= (int)$r['duree'] ?>h</td>
                    <td class="prix-cell"><?= number_format($r['prix_total'], 2, ',', ' ') ?> €</td>
                    <td><?= date('d/m/Y H:i', strtotime($r['cree_le'])) ?></td>
                    <td>
                        <a href="admin.php?supprimer=<?= (int)$r['id'] ?>"
                           class="btn-supprimer"
                           onclick="return confirm('Supprimer cette réservation ?')">
                            🗑 Supprimer
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
 
</div>
 
</body>
</html>
