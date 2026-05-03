🌿 Green Desk 
Plateforme de réservation d'espaces de coworking à Paris. Développée en PHP natif avec une base de données MySQL, elle permet aux utilisateurs de consulter les espaces disponibles, de faire une réservation en ligne, et à l'administrateur de suivre toutes les réservations.

Structure du projet
green-desk/
├── index.php       → Page d'accueil : liste des espaces
├── details.php     → Page détail d'un espace + formulaire de réservation
├── admin.php       → Interface d'administration des réservations
├── db.php          → Connexion PDO + fonctions utilitaires
├── header.php      → En-tête HTML commun à toutes les pages
├── footer.php      → Pied de page HTML commun
├── style.css       → Feuille de styles (design complet)
└── green_desk.sql  → Script SQL (création BDD + données de départ)

 Base de données
Nom de la base : green_desk
Table espaces
ColonneTypeDescriptionidINT AUTO_INCREMENTIdentifiant uniquenomVARCHAR(100)Nom de l'espacedescriptionTEXTDescription complèteprix_heureDECIMAL(6,2)Prix à l'heure en €capaciteTINYINTNombre de personnes maximage_urlVARCHAR(255)URL de la photoequipementsVARCHAR(255)Liste séparée par des virgules
Table reservations
ColonneTypeDescriptionidINT AUTO_INCREMENTIdentifiant uniqueespace_idINTClé étrangère → espaces.idnom_clientVARCHAR(100)Nom du clientemailVARCHAR(150)Email du clientdate_resaDATEDate de la réservationheure_debutTIMEHeure de débutdureeTINYINTDurée en heures (1 à 8)prix_totalDECIMAL(8,2)Prix total calculémessageTEXTMessage optionnel du clientcree_leDATETIMEDate/heure d'enregistrement

Installation
Prérequis

WAMP / XAMPP / MAMP (Apache + PHP 8+ + MySQL)
Un navigateur web

Étapes
1. Cloner ou copier le projet
Dépose tous les fichiers dans le dossier de ton serveur local :

WAMP : C:/wamp64/www/green-desk/
XAMPP : C:/xampp/htdocs/green-desk/

2. Créer la base de données

Ouvre phpMyAdmin → http://localhost/phpmyadmin
Clique sur l'onglet Importer
Sélectionne le fichier green_desk.sql
Clique sur Importer

3. Vérifier la configuration BDD
Ouvre db.php et vérifie ces constantes :
phpdefine('DB_HOST', 'localhost');
define('DB_NAME', 'green_desk');
define('DB_USER', 'root');   // ton utilisateur MySQL
define('DB_PASS', '');       // ton mot de passe MySQL
4. Lancer le site
Ouvre ton navigateur et va sur :
http://localhost/green-desk/

 Pages
URLDescription/index.phpAccueil — liste des 4 espaces avec prix et équipements/details.php?id=1Détail d'un espace + formulaire de réservation/admin.phpTableau de bord admin — toutes les réservations

 Fonctionnalités

 Catalogue d'espaces — 4 espaces avec photo, description, capacité, équipements et prix
 Réservation en ligne — formulaire avec validation côté serveur, enregistrement en BDD
 Calcul automatique du prix total selon la durée choisie
 Interface admin — tableau récapitulatif avec chiffre d'affaires et suppression de réservations
 Design responsive — adapté mobile, tablette et desktop
 Accessibilité — balises sémantiques, labels de formulaire, textes alternatifs


 Technologies utilisées
TechnologieUsagePHP 8Logique serveur, traitement des formulairesMySQLStockage des espaces et réservationsPDOConnexion sécurisée à la base de donnéesHTML5Structure des pagesCSS3Design, animations, responsive (CSS Grid & Flexbox)Google FontsPlayfair Display + DM SansPicsum PhotosImages de placeholder pour les espaces

 Sécurité

Requêtes SQL préparées avec PDO (protection contre les injections SQL)
Échappement des sorties avec htmlspecialchars() (protection XSS)
Validation et typage strict des entrées utilisateur
Redirection automatique si l'ID d'espace est invalide


 Auteur
Projet réalisé dans le cadre du BTS SIO option SLAM.


 Green Desk — Des espaces de travail pensés pour votre bien-être et votre productivité.
