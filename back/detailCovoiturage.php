<?php
require_once 'db.php'; // connexion PDO

// 1️⃣ Récupération de l’ID dans l’URL
$idCovoit = $_GET['id'] ?? '';
if (!ctype_digit($idCovoit)) {
    // Redirection si l'id est invalide
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
                    AND d2.statue = 'recu'
                ) AS moyenne,
                pa.role,
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
                a.commentaire
            FROM utilisateur u
            JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
            JOIN gere ge ON ge.utilisateur_utilisateur_id = u.utilisateur_id
            JOIN voiture v ON v.voiture_id = ge.voiture_voiture_id
            LEFT JOIN depose d ON u.utilisateur_id = d.utilisateur_utilisateur_id
            LEFT JOIN avis a ON d.avis_avis_id = a.avis_id
            LEFT JOIN detient de ON de.voiture_voiture_id = v.voiture_id
            LEFT JOIN marque m ON m.marque_id = de.marque_marque_id
            WHERE c.covoiturage_id = :id
            ORDER BY c.heure_depart ASC";

$stmt = $pdo->prepare($sqlDetail);
$stmt->execute(['id' => $idCovoit]);
$covoit = $stmt->fetch(PDO::FETCH_ASSOC);

// Fonction date covoiturage
$dateDetail = new DateTime($covoit['date_depart']); // Convertie la variable en DateTime
$fmt = new IntlDateFormatter(                       // Formater la date en format FR
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE
);
$dateDetailCovoit = $fmt->format($dateDetail);
$dateDetailCovoit = mb_convert_case($dateDetailCovoit, MB_CASE_TITLE, "UTF-8");
?>
