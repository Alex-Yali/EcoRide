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
$idUtilisateur = $_SESSION['user_id'];
$covoiturage_id = $_POST['covoiturage_id'] ?? 0;

try {
    // Récupération des covoiturages actifs
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
                        u_conducteur.pseudo AS conducteur_pseudo,
                        u_conducteur.utilisateur_id AS conducteur_id,
                        (
                            SELECT AVG(a2.note)
                            FROM avis a2
                            WHERE a2.chauffeur_id = u_conducteur.utilisateur_id
                            AND a2.statut = 'valider'
                        ) AS conducteur_moyenne
                    FROM covoiturage c
                    JOIN participe pa ON pa.covoiturage_covoiturage_id = c.covoiturage_id
                    JOIN utilisateur u ON u.utilisateur_id = pa.utilisateur_utilisateur_id
                    LEFT JOIN participe p_conducteur 
                        ON p_conducteur.covoiturage_covoiturage_id = c.covoiturage_id
                        AND p_conducteur.chauffeur = 1
                    LEFT JOIN utilisateur u_conducteur ON u_conducteur.utilisateur_id = p_conducteur.utilisateur_utilisateur_id
                    JOIN utilise ut ON ut.covoiturage_covoiturage_id = c.covoiturage_id
                    JOIN voiture v ON v.voiture_id = ut.voiture_voiture_id
                    WHERE pa.utilisateur_utilisateur_id = :idUtilisateur
                    AND (c.statut IS NULL OR c.statut NOT IN ('Terminer','Annuler','Valider'))
                    ORDER BY c.date_depart ASC, c.heure_depart ASC
                ";
    
    $stmtMesCovoit = $pdo->prepare($sqlMesCovoit);
    $stmtMesCovoit->execute(['idUtilisateur' => $idUtilisateur]);
    $mesCovoit = $stmtMesCovoit->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($mesCovoit)) {
        $fmt = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        foreach ($mesCovoit as &$covoit) {
            $fDate = new DateTime($covoit['date_depart']);
            $covoit['date_formatee'] = ucfirst($fmt->format($fDate));
        }
        unset($covoit);
    } else {
        $message = "<p>Aucun covoiturage trouvé.</p>";
    }

    // Gestion des actions POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $covoiturage_id > 0) {

        // Vérification CSRF
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            throw new Exception("Erreur CSRF : requête invalide.");
        }

        $action = $_POST['action'] ?? '';
        $statut = match ($action) {
            'demarrer' => 'Demarrer',
            'terminer' => 'Terminer',
            'annuler' => 'Annuler',
            default => null
        };

        if ($statut !== null) {
            // Vérifie le rôle de l’utilisateur
            $sqlCheck = "SELECT chauffeur FROM participe WHERE covoiturage_covoiturage_id = ? AND utilisateur_utilisateur_id = ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$covoiturage_id, $idUtilisateur]);
            $participant = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if (!$participant) {
                die("Action non autorisée.");
            }

            // Trajet terminé par le chauffeur"
            if ($statut === 'Terminer' && $participant['chauffeur'] == 1) {
                // Récupérer infos du covoiturage
                $sqlInfos = "SELECT lieu_depart, lieu_arrivee, date_depart, heure_depart, prix_personne FROM covoiturage WHERE covoiturage_id = ?";
                $stmtInfos = $pdo->prepare($sqlInfos);
                $stmtInfos->execute([$covoiturage_id]);
                $covoitInfos = $stmtInfos->fetch(PDO::FETCH_ASSOC);

                // Récupérer les passagers
                $sqlPassagers = "SELECT u.email, u.pseudo FROM participe p 
                                JOIN utilisateur u ON u.utilisateur_id = p.utilisateur_utilisateur_id 
                                WHERE p.covoiturage_covoiturage_id = ? AND p.chauffeur = 0";
                $stmtPassagers = $pdo->prepare($sqlPassagers);
                $stmtPassagers->execute([$covoiturage_id]);
                $passagers = $stmtPassagers->fetchAll(PDO::FETCH_ASSOC);

                // Envoi d’email aux passagers
                foreach ($passagers as $p) {
                    $to = $p['email'];
                    $subject = "Arrivée à destination";
                    $messageMail = "
                    Bonjour {$p['pseudo']},<br><br>
                    Votre covoiturage de <b>{$covoitInfos['lieu_depart']}</b> à <b>{$covoitInfos['lieu_arrivee']}</b><br>
                    du <b>{$covoitInfos['date_depart']}</b à <b>{$covoitInfos['heure_depart']}</b> est arrivé à destination.<br><br>
                    Merci d’avoir voyagé avec EcoRide !<br>
                    Vous pouvez maintenant laisser un avis sur votre conducteur dans l'historique de vos covoiturages dans votre espace .<br><br>
                    <hr>
                    <i>L’équipe EcoRide</i>";
                    @mail($to, $subject, $messageMail);
                }
            }

            // Si Annulation
            if ($statut === 'Annuler') {
                $sqlPrix = "SELECT prix_personne, nb_place, lieu_depart, lieu_arrivee, date_depart, heure_depart 
                            FROM covoiturage WHERE covoiturage_id = ?";
                $stmtPrix = $pdo->prepare($sqlPrix);
                $stmtPrix->execute([$covoiturage_id]);
                $covoitInfos = $stmtPrix->fetch(PDO::FETCH_ASSOC);

                $prix = $covoitInfos['prix_personne'] ?? 0;

                // Vérifie si l'utilisateur est conducteur ou passager
                if ($participant['chauffeur'] == 1) {
                    //  --- Le chauffeur annule le trajet ---

                    //  Statut annuler
                    $sqlStatut = "UPDATE covoiturage SET statut = 'Annuler' WHERE covoiturage_id = ?";
                    $stmtStatut = $pdo->prepare($sqlStatut);
                    $stmtStatut->execute([$covoiturage_id]);

                    // Récupérer tous les passagers
                    $sqlPassagers = "SELECT u.utilisateur_id, u.email, u.pseudo 
                                    FROM participe p
                                    JOIN utilisateur u ON u.utilisateur_id = p.utilisateur_utilisateur_id
                                    WHERE p.covoiturage_covoiturage_id = ? AND p.chauffeur = 0";
                    $stmtPassagers = $pdo->prepare($sqlPassagers);
                    $stmtPassagers->execute([$covoiturage_id]);
                    $passagers = $stmtPassagers->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($passagers as $p) {
                        // 1️ Remboursement du passager
                        $sqlCredit = "UPDATE utilisateur SET credits = credits + ? WHERE utilisateur_id = ?";
                        $stmtCredit = $pdo->prepare($sqlCredit);
                        $stmtCredit->execute([$prix, $p['utilisateur_id']]);

                        // 2️ Envoi d’un email d’annulation
                        $to = $p['email'];
                        $subject = "Annulation du covoiturage";
                        $messageMail = "
                        Bonjour {$p['pseudo']},<br><br>
                        Le conducteur a annulé le covoiturage prévu de 
                        <b>{$covoitInfos['lieu_depart']}</b> à <b>{$covoitInfos['lieu_arrivee']}</b><br>
                        le <b>{$covoitInfos['date_depart']}</b> à <b>{$covoitInfos['heure_depart']}</b>.<br><br>
                        Vos crédits ont été remboursés automatiquement.<br><br>
                        Merci de votre compréhension.<br>
                        <hr>
                        <i>L’équipe EcoRide</i>";

                        @mail($to, $subject, $messageMail);
                    }

                    // 3️ Supprimer toutes les participations
                    $sqlDelete = "DELETE FROM participe WHERE covoiturage_covoiturage_id = ?";
                    $stmtDelete = $pdo->prepare($sqlDelete);
                    $stmtDelete->execute([$covoiturage_id]);

                    // 4️ Remettre toutes les places disponibles
                    $nbPlacesTotales = count($passagers) + $covoitInfos['nb_place'];
                    $sqlPlacesTotales = "UPDATE covoiturage SET nb_place = ? WHERE covoiturage_id = ?";
                    $stmtPlacesTotales = $pdo->prepare($sqlPlacesTotales);
                    $stmtPlacesTotales->execute([$nbPlacesTotales, $covoiturage_id]);

                }
            }
            if ($participant['chauffeur'] == 0) {
                    //  --- Un passager annule ---
                    if ($prix > 0) {
                        // 1️ Remboursement du passager
                        $sqlPrixPassager = "UPDATE utilisateur SET credits = credits + ? WHERE utilisateur_id = ?";
                        $stmtPrixPassager = $pdo->prepare($sqlPrixPassager);
                        $stmtPrixPassager->execute([$prix, $idUtilisateur]);

                        // 2️ Libérer une place (+1)
                        $sqlPlacePassager = "UPDATE covoiturage SET nb_place = nb_place + 1 WHERE covoiturage_id = ?";
                        $stmtPlacePassager = $pdo->prepare($sqlPlacePassager);
                        $stmtPlacePassager->execute([$covoiturage_id]);

                        // 3️ Supprimer la participation du passager
                        $sqlDeleteParticipe = "DELETE FROM participe WHERE covoiturage_covoiturage_id = ? AND utilisateur_utilisateur_id = ?";
                        $stmtDeleteParticipe = $pdo->prepare($sqlDeleteParticipe);
                        $stmtDeleteParticipe->execute([$covoiturage_id, $idUtilisateur]);
                    }
                }
            // Rafraîchissement
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

} catch (PDOException $e) {
    echo "<p>Erreur : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
}
?>
