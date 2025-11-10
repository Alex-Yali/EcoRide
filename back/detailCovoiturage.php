<?php
require_once 'db.php'; // Connexion PDO

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1️⃣ Récupération de l’ID dans l’URL
$idCovoit = $_GET['id'] ?? '';
if (!ctype_digit($idCovoit)) {
    header('Location: covoiturage.php');
    exit;
}

// 2️⃣ Requête SQL pour récupérer les infos du covoiturage
$sqlDetail = "SELECT 
                u.utilisateur_id,
                u.pseudo,
                a.note,
                (
                    SELECT AVG(a2.note)
                    FROM depose d2
                    JOIN avis a2 ON d2.avis_avis_id = a2.avis_id
                    WHERE d2.utilisateur_utilisateur_id = u.utilisateur_id
                    AND d2.statut = 'recu'
                ) AS moyenne,
                c.covoiturage_id,
                c.lieu_depart,
                c.date_depart,
                c.heure_depart,
                c.lieu_arrivee,
                c.date_arrivee,
                c.heure_arrivee,
                c.nb_place,
                c.prix_personne,
                v.voiture_id,
                v.modele,
                v.energie,
                m.marque_id,
                m.libelle AS marqueVoiture,
                a.commentaire,
                p.preference_id,
                p.libelle AS preferences
            FROM utilisateur u
            LEFT JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
            LEFT JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id
            LEFT JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id
            LEFT JOIN depose d ON u.utilisateur_id = d.utilisateur_utilisateur_id
            LEFT JOIN avis a ON d.avis_avis_id = a.avis_id
            LEFT JOIN detient de ON de.voiture_voiture_id = v.voiture_id
            LEFT JOIN marque m ON m.marque_id = de.marque_marque_id
            LEFT JOIN fournir f ON f.utilisateur_utilisateur_id = pa.utilisateur_utilisateur_id
            LEFT JOIN preference p ON p.preference_id = f.preference_preference_id
            WHERE c.covoiturage_id = :id
            AND pa.chauffeur = 1
            ";

$stmt = $pdo->prepare($sqlDetail);
$stmt->execute(['id' => $idCovoit]);
$covoit = $stmt->fetchAll(PDO::FETCH_ASSOC);

// On prend la première ligne pour les infos générales
$covoitDetail = $covoit[0];

// On regroupe les préférences (car il peut y en avoir plusieurs)
$preferences = array_unique(array_column($covoit, 'preferences'));

// Fonction date covoiturage (en français)
$dateDetail = new DateTime($covoitDetail['date_depart']);
$fmt = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE
);
$dateDetailCovoit = mb_convert_case($fmt->format($dateDetail), MB_CASE_TITLE, "UTF-8");
