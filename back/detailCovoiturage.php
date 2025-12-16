<?php
require_once 'db.php'; // Connexion PDO
require_once 'back/infosUtilisateur.php';
require_once 'back/mongo.php';
require_once 'csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1️ Récupération de l’ID dans l’URL
$idCovoit = $_GET['id'] ?? '';
if (!ctype_digit($idCovoit)) {
    header('Location: covoiturage.php');
    exit;
}
try { 
    // 2️ Requête SQL pour récupérer les infos du covoiturage
    $sqlDetail = "SELECT 
                    u.utilisateur_id,
                    u.pseudo,
                    u.credits,
                    a.note,
                    (
                        SELECT AVG(a2.note)
                        FROM avis a2
                        WHERE a2.chauffeur_id = u.utilisateur_id
                        AND a2.statut = 'valider'
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
                    a.commentaire
                FROM utilisateur u
                LEFT JOIN participe pa ON pa.utilisateur_utilisateur_id = u.utilisateur_id
                JOIN covoiturage c ON c.covoiturage_id = pa.covoiturage_covoiturage_id
                LEFT JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id
                LEFT JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id
                LEFT JOIN depose d ON u.utilisateur_id = d.utilisateur_utilisateur_id
                LEFT JOIN avis a ON d.avis_avis_id = a.avis_id
                LEFT JOIN detient de ON de.voiture_voiture_id = v.voiture_id
                LEFT JOIN marque m ON m.marque_id = de.marque_marque_id
                WHERE c.covoiturage_id = :id
                AND pa.chauffeur = 1
                ";

    $stmt = $pdo->prepare($sqlDetail);
    $stmt->execute(['id' => $idCovoit]);
    $covoit = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // On prend la première ligne pour les infos générales
    $covoitDetail = $covoit[0];

    // Gerer les preferences
    $preferences = []; 

    $idChauffeur = $covoitDetail['utilisateur_id'] ?? null;

    if ($idChauffeur) {
        $doc = $collectionPreferences->findOne([
            'utilisateur_id' => (int)$idChauffeur
        ]);

        if ($doc && !empty($doc['preferences'])) {
            foreach ($doc['preferences'] as $key => $value) {
                if (!empty($value)) {
                    $preferences[] = "$key : $value";
                }
            }
        }
    }

    // Fonction date covoiturage (en français)
    $dateDetail = new DateTime($covoitDetail['date_depart']);
    $fmt = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE
    );
    $dateDetailCovoit = mb_convert_case($fmt->format($dateDetail), MB_CASE_TITLE, "UTF-8");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'oui') {

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            throw new Exception("Erreur CSRF : requête invalide.");
        }

        // Enlever crédits a l'utilisateur
        $prixCovoit = $covoitDetail['prix_personne'];
        $sqlRemoveCredits = "UPDATE utilisateur 
                            SET credits = credits - ? 
                            WHERE utilisateur_id = ?";
        $stmtRemoveCredits = $pdo->prepare($sqlRemoveCredits);
        $stmtRemoveCredits->execute([$prixCovoit, $idUtilisateur]);

        // Ajouter utilisateur au covoiturage
        $idCovoit = $covoitDetail['covoiturage_id'];
        $sqlAddUtilisateur = "INSERT INTO participe (utilisateur_utilisateur_id, covoiturage_covoiturage_id, passager)
                            VALUES (:utilisateur, :covoiturage, :passager)";
        $stmtAddUtilisateur = $pdo->prepare($sqlAddUtilisateur);
        $stmtAddUtilisateur->execute([
            ':utilisateur' => $idUtilisateur,
            ':covoiturage' => $idCovoit,
            ':passager' => 1
        ]);

        // Enlever place dispo au covoiturage
        $sqlRemovePlace = " UPDATE covoiturage
                            SET nb_place = nb_place - 1
                            WHERE covoiturage_id = ?";
        $stmtRemovePlace = $pdo->prepare($sqlRemovePlace);
        $stmtRemovePlace->execute([$idCovoit]);
    }

        // Fonction check si participe deja au covoiturage
        function participeDeja($pdo, $idUtilisateur, $idCovoit) {
            $sqlCheck = "SELECT COUNT(*) FROM participe p
                        JOIN covoiturage c ON c.covoiturage_id = p.covoiturage_covoiturage_id 
                        WHERE c.covoiturage_id = :covoiturage
                        AND p.utilisateur_utilisateur_id = :utilisateur
                        AND p.passager = :passager";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([
                ':utilisateur' =>$idUtilisateur,
                ':covoiturage' => $idCovoit,
                ':passager' => 1
                ]);
        return $stmtCheck->fetchColumn() > 0 ;
        }

}catch (PDOException $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}