<?php
require_once 'db.php';
require_once 'csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$idUtilisateur = $_SESSION['user_id'] ?? null;
$covoiturage_id = intval($_POST['covoiturage_id'] ?? 0);

try { 
    $sqlMesCovoit = "SELECT 
                        u.utilisateur_id,
                        u.pseudo,
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
                        c.statut,
                        u_conducteur.pseudo AS conducteur_pseudo, -- Récupère le pseudo du conducteur
                        u_conducteur.utilisateur_id AS conducteur_id,
                        (
                        SELECT AVG(a2.note)
                        FROM avis a2
                        WHERE a2.chauffeur_id = conducteur_id
                        AND a2.statut = 'valider'
                        ) AS conducteur_moyenne
                    FROM covoiturage c
                    JOIN participe pa ON pa.covoiturage_covoiturage_id = c.covoiturage_id
                    JOIN utilisateur u ON u.utilisateur_id = pa.utilisateur_utilisateur_id
                    LEFT JOIN participe p_conducteur 
                        ON p_conducteur.covoiturage_covoiturage_id = c.covoiturage_id
                        AND p_conducteur.chauffeur = 1
                    LEFT JOIN utilisateur u_conducteur ON u_conducteur.utilisateur_id = p_conducteur.utilisateur_utilisateur_id
                    JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id -- On relie le covoiturage à la voiture qu’il utilise 
                    JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id -- Récupère les infos de la voiture utilisée
                    WHERE pa.utilisateur_utilisateur_id = :idUtilisateur -- Récupères que les covoiturages auxquels l’utilisateur connecté participe
                    AND c.statut IN ('Terminer','Annuler','Valider')
                    ORDER BY c.date_depart ASC, c.heure_depart ASC
                    ";

$stmtMesCovoit = $pdo->prepare($sqlMesCovoit);
$stmtMesCovoit->execute(['idUtilisateur' => $idUtilisateur]);
$mesCovoit = $stmtMesCovoit->fetchAll(PDO::FETCH_ASSOC);


if (!empty($mesCovoit)) {
    $fmt = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

    foreach ($mesCovoit as &$covoit) { // & pour modifier directement l'élément
        $fDate = new DateTime($covoit['date_depart']);
        $covoit['date_formatee'] = ucfirst($fmt->format($fDate)); // nouvelle clé
    }
    unset($covoit); 
} else {
        $message = "<p>Aucun covoiturage trouvé.</p>";
    } 

        // Fonction check si avis déjà donné
        function avisDejaDonne($pdo, $idUtilisateur, $covoiturage_id, $conducteur_id) {
            $sqlCheck = "SELECT COUNT(*) FROM depose d
                        JOIN avis a ON a.avis_id = d.avis_avis_id 
                        WHERE a.covoiturage_id = :covoiturage
                        AND d.utilisateur_utilisateur_id = :utilisateur
                        AND a.chauffeur_id = :chauffeur";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([
                ':utilisateur' =>$idUtilisateur,
                ':covoiturage' => $covoiturage_id,
                ':chauffeur' => $conducteur_id
                ]);
        return $stmtCheck->fetchColumn() > 0 ;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'envoyer') {

        // Vérification CSRF
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            throw new Exception("Erreur CSRF : requête invalide.");
        }

        // Récupération des données du formulaire
        $avis = $_POST['avis'] ?? '';
        $rating = $_POST['rating'] ?? '';
        $commentaire = trim($_POST['commentaire'] ?? '');
        $covoiturage_id = intval($_POST['covoiturage_id'] ?? 0);



        // Chercher le covoiturage correspondant
        $prixParPersonne = 0;
        $conducteur_id = 0;
        foreach ($mesCovoit as $co) {
            if ($co['covoiturage_id'] == $covoiturage_id) {
                $prixParPersonne = $co['prix_personne'];
                $conducteur_id = $co['conducteur_id'];
                break;
            }
        }

        // Avis = oui
        if ($avis === 'Oui' && $prixParPersonne > 0) {
        // Ajouter avis conducteur
            $sqlAddAvis = "INSERT INTO avis (commentaire, note, statut, chauffeur_id, covoiturage_id,etat)
                            VALUES (:commentaire, :note, :statut, :chauffeur, :covoiturage, :etat)";
            $stmtAddAvis = $pdo->prepare($sqlAddAvis);
            $stmtAddAvis->execute([
                ':commentaire' => $commentaire,
                ':note' => $rating,
                ':statut' => 'en attente',
                ':chauffeur' => $conducteur_id,
                ':covoiturage' => $covoiturage_id,
                ':etat'=> 'ok'
        ]);
        $idAvis = $pdo->lastInsertId();

        $sqlAddDepose = "INSERT INTO depose (utilisateur_utilisateur_id, avis_avis_id)
                        VALUES (:utilisateur, :avis)";
        $stmtAddDepose = $pdo->prepare($sqlAddDepose);
        $stmtAddDepose->execute([
            ':utilisateur' => $idUtilisateur,
            ':avis' => $idAvis
        ]);

        // Ajouter crédits au conducteur
        $sqlAddCredits = "UPDATE utilisateur 
                        SET credits = credits + ? 
                        WHERE utilisateur_id = ?";
        $stmtAddCredits = $pdo->prepare($sqlAddCredits);
        $stmtAddCredits->execute([$prixParPersonne, $conducteur_id]);
        }

        // Avis = non
        if ($avis === 'Non' && $prixParPersonne > 0) {
            // Ajouter avis conducteur
            $sqlAddAvis = "INSERT INTO avis (commentaire, note, statut, chauffeur_id, covoiturage_id,etat)
                            VALUES (:commentaire, :note, :statut, :chauffeur, :covoiturage, :etat)";
            $stmtAddAvis = $pdo->prepare($sqlAddAvis);
            $stmtAddAvis->execute([
                ':commentaire' => $commentaire,
                ':note' => $rating,
                ':statut' => 'en attente',
                ':chauffeur' => $conducteur_id,
                ':covoiturage' => $covoiturage_id,
                ':etat'=> 'nok'
            ]);
            $idAvis = $pdo->lastInsertId();

            $sqlAddDepose = "INSERT INTO depose (utilisateur_utilisateur_id, avis_avis_id)
                            VALUES (:utilisateur, :avis)";
            $stmtAddDepose = $pdo->prepare($sqlAddDepose);
            $stmtAddDepose->execute([
                ':utilisateur' => $idUtilisateur,
                ':avis' => $idAvis
            ]);
        }

    }

}catch (PDOException $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}