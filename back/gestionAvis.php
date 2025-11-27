<?php
require_once 'db.php'; // connexion PDO
require_once 'infosUtilisateur.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupérer les avis
$sqlAvis = "SELECT 
                u.utilisateur_id,
                u.pseudo,
                c.prix_personne,
                ua.utilisateur_id AS auteur_id,
                ua.pseudo AS auteur_pseudo,
                a.commentaire,
                a.chauffeur_id,
                a.avis_id,
                a.etat,
                a.note,
                (
                    SELECT AVG(a2.note)
                    FROM avis a2
                    WHERE a2.chauffeur_id = u.utilisateur_id
                    AND a2.statut = 'valider'
                ) AS moyenne
            FROM utilisateur u
            JOIN avis a ON a.chauffeur_id = u.utilisateur_id
            JOIN depose d ON d.avis_avis_id = a.avis_id
            JOIN utilisateur ua ON ua.utilisateur_id = d.utilisateur_utilisateur_id  -- auteur de l'avis
            JOIN covoiturage c ON c.covoiturage_id = a.covoiturage_id
            WHERE a.statut = 'en attente'
            ORDER BY a.avis_id ASC
            ";

$stmtAvis = $pdo->prepare($sqlAvis);
$stmtAvis->execute();
$avis = $stmtAvis->fetchAll(PDO::FETCH_ASSOC);

// Valider les avis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['valider'])) {

    $idAvis = $_POST['valider'];

    // Récupérer les infos de cet avis
    $sqlInfo = "SELECT a.etat, c.prix_personne, a.chauffeur_id
                FROM avis a
                JOIN covoiturage c ON c.covoiturage_id = a.covoiturage_id
                WHERE a.avis_id = :id";

    $stmtInfo = $pdo->prepare($sqlInfo);
    $stmtInfo->execute([':id' => $idAvis]);
    $info = $stmtInfo->fetch(PDO::FETCH_ASSOC);

    $etat = $info['etat'];
    $prixParPersonne = $info['prix_personne'];
    $idChauffeur = $info['chauffeur_id'];

    // Ajouter crédits si valider et etat 'nok'
    if ($etat === 'nok') {
        $sqlAddCredits = "UPDATE utilisateur 
                        SET credits = credits + :credit 
                        WHERE utilisateur_id = :id";
        $stmtAddCredits = $pdo->prepare($sqlAddCredits);
        $stmtAddCredits->execute([
            ':credit' => $prixParPersonne,
            ':id' => $idChauffeur
        ]);
    }

    // Valider l'avis
    $sqlValider = "UPDATE avis SET statut = 'valider', employe_id = :idEmploye WHERE avis_id = :id";
    $stmtValider = $pdo->prepare($sqlValider);
    $stmtValider->execute([
        ':id' => $idAvis,
        ':idEmploye' => $idUtilisateur
    ]);

    header("Location: ../avisEnCours.php");
    exit;
}

// Refuser les avis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['refuser'])) {

    $idAvis = $_POST['refuser'];

    $sqlRefuser = "UPDATE avis SET statut = 'refuser', employe_id = :idEmploye WHERE avis_id = :id";
    $stmtRefuser = $pdo->prepare($sqlRefuser);
    $stmtRefuser->execute([
        ':id' => $idAvis,
        ':idEmploye' => $idUtilisateur
    ]);
    header("Location: ../avisEnCours.php");
    exit;
}

// Récupérer les infos du voyage
if (!isset($_GET['avis_id'])) {
    $infos = null; 
} else {
    $sqlnfos = "SELECT 
                    a.covoiturage_id,
                    ua.pseudo AS passager_pseudo,
                    ua.email AS passager_email,
                    u.pseudo AS chauffeur_pseudo,
                    u.email AS chauffeur_email,
                    c.date_depart,
                    c.lieu_depart,
                    c.date_arrivee,
                    c.lieu_arrivee
                FROM avis a
                JOIN depose d ON d.avis_avis_id = a.avis_id
                JOIN utilisateur ua ON ua.utilisateur_id = d.utilisateur_utilisateur_id  -- passager
                JOIN utilisateur u ON u.utilisateur_id = a.chauffeur_id -- chauffeur
                JOIN covoiturage c ON c.covoiturage_id = a.covoiturage_id
                WHERE a.statut = 'en attente'
                AND a.avis_id = :avis_id
                ";

    $stmtInfos = $pdo->prepare($sqlnfos);
    $stmtInfos->bindValue(':avis_id', $_GET['avis_id'], PDO::PARAM_INT);
    $stmtInfos->execute();
    $infos = $stmtInfos->fetch(PDO::FETCH_ASSOC);
}

// Historique des avis
$sqlAvisCheck = "SELECT 
                    a.avis_id,
                    a.employe_id,
                    a.statut,
                    a.note,
                    a.commentaire,
                    ua.pseudo AS auteur_pseudo 
                FROM avis a
                JOIN depose d ON d.avis_avis_id = a.avis_id
                JOIN utilisateur ua ON ua.utilisateur_id = d.utilisateur_utilisateur_id  -- passager
                WHERE statut IN ('valider','refuser')
                AND a.employe_id = :idEmploye
                ";
$stmtAvisCheck = $pdo->prepare($sqlAvisCheck);
$stmtAvisCheck->execute([
    ':idEmploye' => $idUtilisateur
]);
$avisCheck = $stmtAvisCheck->fetchAll(PDO::FETCH_ASSOC);
?>
