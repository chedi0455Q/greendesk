-- ============================================================
--  GREEN DESK — Script de création de la base de données
--  BTS SIO SLAM | Projet Portfolio n°2
--  À exécuter une seule fois dans phpMyAdmin ou via MySQL CLI
-- ============================================================

-- 1. Création de la base de données (si elle n'existe pas déjà)
CREATE DATABASE IF NOT EXISTS green_desk
    CHARACTER SET utf8mb4       -- Encodage universel (emojis, accents...)
    COLLATE utf8mb4_unicode_ci; -- Comparaisons insensibles à la casse

-- 2. On se positionne sur cette base pour les requêtes suivantes
USE green_desk;

-- 3. Suppression de la table si elle existe déjà (pratique en développement)
DROP TABLE IF EXISTS espaces;

-- 4. Création de la table principale "espaces"
CREATE TABLE espaces (
    id          INT          AUTO_INCREMENT PRIMARY KEY, -- Clé primaire auto-incrémentée
    nom         VARCHAR(100) NOT NULL,                   -- Nom de l'espace (obligatoire)
    description TEXT         NOT NULL,                   -- Description longue
    prix_heure  DECIMAL(6,2) NOT NULL,                   -- Prix en euros (ex: 12.50)
    capacite    TINYINT      NOT NULL,                   -- Nombre de personnes max
    image_url   VARCHAR(255) DEFAULT NULL,               -- Chemin vers l'image (optionnel)
    equipements VARCHAR(255) DEFAULT NULL                -- Liste de mots-clés séparés par virgules
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
--  5. Insertion des données de démonstration (4 espaces)
-- ============================================================

INSERT INTO espaces (nom, description, prix_heure, capacite, image_url, equipements) VALUES

(
    'L\'Open Space Calme',
    'Un grand plateau lumineux baigné de lumière naturelle, idéal pour les freelances et les équipes en mode "deep work". Ambiance zen garantie grâce à notre politique silence et nos plantes purificatrices d\'air.',
    8.00,
    20,
    'assets/img/open-space.jpg',
    'Wi-Fi Fibre,Café & Thé,Climatisation,Casiers sécurisés,Imprimante'
),

(
    'La Salle de Réunion Zen',
    'Un espace fermé et feutré pour vos réunions clients, brainstormings ou entretiens RH. Équipée d\'un grand écran mural et d\'un système de visioconférence, elle accueille confortablement jusqu\'à 8 personnes autour d\'une grande table.',
    25.00,
    8,
    'assets/img/salle-reunion.jpg',
    'Wi-Fi Fibre,Écran 4K,Visioconférence,Tableau blanc,Café & Thé,Climatisation'
),

(
    'Le Bureau Privé Verde',
    'Votre propre bureau fermé à clé pour une journée ou une semaine. Idéal si vous avez besoin de discrétion ou de recevoir des clients dans un cadre professionnel dédié. Vue sur le jardin intérieur.',
    18.50,
    4,
    'assets/img/bureau-prive.jpg',
    'Wi-Fi Fibre,Café & Thé,Climatisation,Casier dédié,Téléphone fixe'
),

(
    'L\'Atelier Créatif',
    'Un espace atypique aux murs blancs, pensé pour les designers, photographes et makers. Hauteur sous plafond de 4m, grandes tables modulables et mur en ardoise pour laisser libre cours à votre créativité collective.',
    15.00,
    12,
    'assets/img/atelier-creatif.jpg',
    'Wi-Fi Fibre,Projecteur HD,Tableau ardoise,Imprimante A3,Café & Thé'
);

-- ============================================================
--  Vérification : afficher le contenu inséré
-- ============================================================
-- SELECT * FROM espaces;
