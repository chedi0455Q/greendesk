<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'green_desk');
define('DB_USER', 'root');       
define('DB_PASS', '');           
define('DB_CHARSET', 'utf8mb4'); 


try {

    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        
        PDO::ATTR_EMULATE_PREPARES   => false,                   
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

} catch (PDOException $e) {

    die('<p style="color:red;font-family:monospace;">Erreur de connexion BDD : ' . htmlspecialchars($e->getMessage()) . '</p>');
}




function getEquipements(string $string): string
{

    if (empty(trim($string))) {
        return '';
    }

    $items = array_map('trim', explode(',', $string));


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

    $html = '<div class="equipements-list">';

    foreach ($items as $item) {
 
        $icone = $icones[$item] ?? '✔';

        $html .= '<span class="badge-equipement">' . $icone . ' ' . htmlspecialchars($item) . '</span>';
    }

    $html .= '</div>';

    return $html;
}


function getEspaces(): array
{
    global $pdo; 

    $stmt = $pdo->query('SELECT * FROM espaces ORDER BY prix_heure ASC');
    return $stmt->fetchAll(); 
}

function getEspaceById(int $id)
{
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM espaces WHERE id = :id');
    $stmt->execute([':id' => $id]); 

    return $stmt->fetch(); 
}
