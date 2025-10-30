<?php
require_once 'back/db.php';

$message = "";

$depart  = trim($_POST['depart'] ?? '');
$arrivee = trim($_POST['arrivee'] ?? '');
$date    = trim($_POST['date'] ?? '');

if (!empty($depart) && !empty($arrivee) && !empty($date)) {
        $sql = "SELECT 
                u.utilisateur_id,
                u.pseudo,
                AVG(a.note) AS moyenne,
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
                v.energie
                FROM utilisateur u
                JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
                JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
                JOIN gere ge ON ge.utilisateur_utilisateur_id = u.utilisateur_id
                JOIN voiture v ON v.voiture_id = ge.voiture_voiture_id
                JOIN depose d ON u.utilisateur_id = d.utilisateur_utilisateur_id
                JOIN avis a ON d.avis_avis_id = a.avis_id
                WHERE c.lieu_depart = :depart
                AND c.lieu_arrivee = :arrivee
                AND c.date_depart = :date
                AND pa.role = 'conducteur'
                GROUP BY u.utilisateur_id,
                         u.pseudo,
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
                         v.energie
                ORDER BY c.heure_depart";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
                        ':depart'  => $depart,
                        ':arrivee' => $arrivee,
                        ':date'    => $date
                        ]);
        $covoits = $stmt->fetchAll(PDO::FETCH_ASSOC);


    if (!$covoits) {
        $message = "Aucun covoiturage trouvé pour cette recherche.";
    }

} else {
    $message = "Merci de remplir tous les champs.";
}
?>
