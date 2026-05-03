CREATE DATABASE IF NOT EXISTS green_desk
    CHARACTER SET utf8mb4       
    COLLATE utf8mb4_unicode_ci; 
 
USE green_desk;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS espaces;
 
CREATE TABLE espaces (
    id          INT          AUTO_INCREMENT PRIMARY KEY, 
    nom         VARCHAR(100) NOT NULL,                   
    description TEXT         NOT NULL,                  
    prix_heure  DECIMAL(6,2) NOT NULL,                   
    capacite    TINYINT      NOT NULL,                
    image_url   VARCHAR(255) DEFAULT NULL,              
    equipements VARCHAR(255) DEFAULT NULL                
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
CREATE TABLE reservations (
    id           INT           AUTO_INCREMENT PRIMARY KEY,
    espace_id    INT           NOT NULL,
    nom_client   VARCHAR(100)  NOT NULL,
    email        VARCHAR(150)  NOT NULL,
    date_resa    DATE          NOT NULL,
    heure_debut  TIME          NOT NULL,
    duree        TINYINT       NOT NULL,
    prix_total   DECIMAL(8,2)  NOT NULL,
    message      TEXT          DEFAULT NULL,
    cree_le      DATETIME      DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (espace_id) REFERENCES espaces(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 
INSERT INTO espaces (nom, description, prix_heure, capacite, image_url, equipements) VALUES
(
    'L\'Open Space Calme',
    'Un grand plateau lumineux baigné de lumière naturelle, idéal pour les freelances et les équipes en mode "deep work". Ambiance zen garantie grâce à notre politique silence et nos plantes purificatrices d\'air.',
    8.00,
    20,
    'https://picsum.photos/id/3/800/500',
    'Wi-Fi Fibre,Café & Thé,Climatisation,Casiers sécurisés,Imprimante'
),
(
    'La Salle de Réunion Zen',
    'Un espace fermé et feutré pour vos réunions clients, brainstormings ou entretiens RH. Équipée d\'un grand écran mural et d\'un système de visioconférence, elle accueille confortablement jusqu\'à 8 personnes autour d\'une grande table.',
    25.00,
    8,
    'https://picsum.photos/id/20/800/500',
    'Wi-Fi Fibre,Écran 4K,Visioconférence,Tableau blanc,Café & Thé,Climatisation'
),
(
    'Le Bureau Privé Verde',
    'Votre propre bureau fermé à clé pour une journée ou une semaine. Idéal si vous avez besoin de discrétion ou de recevoir des clients dans un cadre professionnel dédié. Vue sur le jardin intérieur.',
    18.50,
    4,
    'https://picsum.photos/id/42/800/500',
    'Wi-Fi Fibre,Café & Thé,Climatisation,Casier dédié,Téléphone fixe'
),
(
    'L\'Atelier Créatif',
    'Un espace atypique aux murs blancs, pensé pour les designers, photographes et makers. Hauteur sous plafond de 4m, grandes tables modulables et mur en ardoise pour laisser libre cours à votre créativité collective.',
    15.00,
    12,
    'https://picsum.photos/id/60/800/500',
    'Wi-Fi Fibre,Projecteur HD,Tableau ardoise,Imprimante A3,Café & Thé'
);
