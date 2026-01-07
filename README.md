# EcoRide
Mon projet de formation

# Déployer l'application en local

// 1️- Installer XAMPP //

- Télécharger XAMPP : https://www.apachefriends.org

- Installer XAMPP puis ouvrir le XAMPP Control Panel

- Démarrer le module Apache en cliquant sur Start

// 2- Récuperer le projet //

- Ouvrir : https://github.com/Alex-Yali/EcoRide

- Cliquer sur le bouton Code puis sur Download ZIP

- Dézipper le dossier dans C:\xampp\htdocs puis le renomer EcoRide

// 3- Ouvrir le projet dans VS Code //


- Télécharger et instaler Visual Studio Code via Microsoft Store

- Démarrer VS Code

- Cliquer sur le menu File (en haut à gauche) puis Open Forlder et selectionner le dossier EcoRide

- L'ensemble du code apparait dans VS Code

// 4- Accéder à la base de données //

- Rechercher le fichier .env.example dans le dossier C:\xampp\htdocs\EcoRide\database

- Faire un copier --> coller de se fichier puis le renommer en .env

- Démarrer le module MySQL dans XAMPP en cliquant sur Start puis sur Admin

- Cliquer sur Nouvelle base de données --> nom : ecoride et Interclassement : utf8mb4_general_ci

- Cliquer sur Créer

- Aller dans la base de donnée ecoride --> Onglet Importer --> Sélectionne le fichier .sql --> Importer

// 5- Activer extention //

- Aller dans le dossier C:\xampp\php\php.ini

- Ouvrir le fichier avec VS Code

- Cliquer sur la loupe puis coller extension=intl

- Cliquer sur le fichier trouvé puis enlever le ; dans la ligne de ;extension=intl puis faire Ctrl + S pour sauvegarder

// 6- Accéder à l'application en local //

- Lancer son navigateur web

- Coller l'adresse : http://localhost/ecoride/index.php

- Vous avez accès à l'application