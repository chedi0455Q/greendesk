<?php
// ============================================================
//  db.php — Connexion à la base de données + fonctions utiles
//  Utilise PDO : interface universelle et sécurisée pour MySQL
// ============================================================

// --- 1. PARAMÈTRES DE CONNEXION ---
// En production, ces valeurs iraient dans un fichier .env
define('DB_HOST', 'localhost');
define('DB_NAME', 'green_desk');
define('DB_USER', 'root');       // À adapter selon votre config WAMP/XAMPP
define('DB_PASS', '');           // Mot de passe MySQL local (souvent vide en dev)
define('DB_CHARSET', 'utf8mb4'); // Encodage universel

// --- 2. CONNEXION PDO ---
// On utilise une variable globale $pdo accessible dans toute l'application
try {
    // Le DSN (Data Source Name) décrit la base à laquelle on se connecte
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    // Options PDO recommandées pour la sécurité et le débogage
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Lance une exception en cas d'erreur SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Retourne des tableaux associatifs par défaut
        PDO::ATTR_EMULATE_PREPARES   => false,                   // Désactive la simulation des requêtes préparées
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {
    // En cas d'erreur de connexion : on arrête et on affiche un message propre
    // IMPORTANT : ne jamais afficher $e->getMessage() en production (sécurité !)
    die('<p style="color:red;font-family:monospace;">Erreur de connexion BDD : ' . htmlspecialchars($e->getMessage()) . '</p>');
}


// ============================================================
//  FONCTIONS UTILITAIRES
// ============================================================

/**
 * getEquipements($string)
 * -------------------------
 * Transforme une chaîne d'équipements (ex: "Wi-Fi,Café,Projecteur")
 * en une liste de badges HTML stylisés.
 *
 * @param  string $string  La valeur du champ `equipements` en base de données
 * @return string          Du HTML prêt à être affiché avec echo
 *
 * Exemple d'appel : echo getEquipements($espace['equipements']);
 */
function getEquipements(string $string): string
{
    // Sécurité : si la chaîne est vide, on ne génère rien
    if (empty(trim($string))) {
        return '';
    }

    // On découpe la chaîne en tableau en utilisant la virgule comme séparateur
    // array_map + trim : on supprime les espaces autour de chaque élément
    $items = array_map('trim', explode(',', $string));

    // Tableau qui associe un emoji à certains équipements reconnus
    // Cela enrichit visuellement les badges sans image externe
    $icones = [
        'Wi-Fi Fibre'      => '📶',
        'Café & Thé'       => '☕',
        'Projecteur HD'    => '📽️',
        'Écran 4K'         => '🖥️',
        'Tableau blanc'    => '🗒️',
        'Tableau ardoise'  => '🖊️',
        'Climatisation'    => '❄️',
        'Visioconférence'  => '📹',
        'Imprimante'       => '🖨️',
        'Imprimante A3'    => '🖨️',
        'Casiers sécurisés'=> '🔒',
        'Casier dédié'     => '🔒',
        'Téléphone fixe'   => '📞',
    ];

    // Construction du HTML : une <span> par équipement
    $html = '<div class="equipements-list">';

    foreach ($items as $item) {
        // On cherche une icône correspondante, sinon on met une puce générique
        $icone = $icones[$item] ?? '✔';

        // htmlspecialchars() protège contre les injections XSS
        $html .= '<span class="badge-equipement">' . $icone . ' ' . htmlspecialchars($item) . '</span>';
    }

    $html .= '</div>';

    return $html;
}


/**
 * getEspaces()
 * -------------------------
 * Récupère tous les espaces disponibles depuis la base de données.
 * Utilise une requête préparée (bonne pratique, même sans paramètre).
 *
 * @return array  Tableau de tous les espaces (tableaux associatifs)
 */
function getEspaces(): array
{
    global $pdo; // On accède à la connexion globale

    $stmt = $pdo->query('SELECT * FROM espaces ORDER BY prix_heure ASC');
    return $stmt->fetchAll(); // Retourne tous les résultats d'un coup
}


/**
 * getEspaceById($id)
 * -------------------------
 * Récupère UN seul espace par son identifiant.
 * Utilise une requête préparée avec paramètre lié (:id)
 * pour éviter les injections SQL.
 *
 * @param  int        $id  L'identifiant passé dans l'URL (ex: ?id=2)
 * @return array|false     Le tableau associatif de l'espace, ou false si introuvable
 */
function getEspaceById(int $id)
{
    global $pdo;

    // Requête préparée : le :id est un marqueur qui sera remplacé de façon sécurisée
    $stmt = $pdo->prepare('SELECT * FROM espaces WHERE id = :id');
    $stmt->execute([':id' => $id]); // Liaison du paramètre

    return $stmt->fetch(); // Retourne un seul enregistrement (ou false)
}
