<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../src/service/db.php'; // connexion PDO
require_once '../src/service/csrf.php';

$idUtilisateur = $_SESSION['user_id'] ?? null; // ID de la personne connectée
$trajetValide = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formType'] ?? '') === 'ajoutTrajet') {

    // Vérification CSRF
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $messageTrajet = "Erreur CSRF : Requête invalide.";
        return;
    }

    $depart = trim($_POST['depart'] ?? '');
    $dateDepart = trim($_POST['dateDepart'] ?? '');
    $heureDepart = trim($_POST['heureDepart'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $dateArrivee = trim($_POST['dateArrivee'] ?? '');
    $heureArrivee = trim($_POST['heureArrivee'] ?? '');
    $places = (int) $_POST['places'];
    $prix = (float) $_POST['prix'];
    $voiture = $_POST['voiture'] ?? '';

    if ($voiture === '') {
        $messageTrajet  = "Veuillez sélectionner une voiture.";
    } else {
        $voiture = (int) $voiture; // conversion en INT pour la base
    }

    // Vérifier si un champ est vide
    if (
        $depart === '' || $dateDepart === '' || $heureDepart === '' || $destination === '' ||
        $dateArrivee === '' || $heureArrivee === '' || $places <= 0 || $prix <= 0  ||  $voiture <= 0
    ) {
        $messageTrajet  = "Veuillez renseigner tous les champs.";
    } else {
        $depart = ucfirst(strtolower($depart));
        $destination = ucfirst(strtolower($destination));

        try {
            $pdo->beginTransaction();

            // 1. Vérifier que la voiture appartient à l'utilisateur via la table 'gere'
            $sqlCheckVoiture = "SELECT voiture_voiture_id 
                                FROM gere 
                                WHERE utilisateur_utilisateur_id = :idUtilisateur 
                                AND voiture_voiture_id = :idVoiture";

            $stmtVoiture = $pdo->prepare($sqlCheckVoiture);
            $stmtVoiture->execute([
                ':idUtilisateur' => $idUtilisateur,
                ':idVoiture' => $voiture
            ]);

            if (!$stmtVoiture->fetch()) {
                $messageTrajet  = "Cette voiture ne vous appartient pas.";
                $trajetValide = false;
                return;
            }

            // 2. Vérifier si l'utilisateur a déjà ce covoiturage
            $sqlCovoit = "SELECT c.covoiturage_id
                        FROM covoiturage c
                        JOIN participe p 
                            ON p.covoiturage_covoiturage_id = c.covoiturage_id
                        WHERE p.utilisateur_utilisateur_id = :idUtilisateur
                            AND c.date_depart = :dateDepart
                            AND c.heure_depart = :heureDepart
                            AND c.lieu_depart = :lieuDepart
                            AND c.date_arrivee = :dateArrivee
                            AND c.heure_arrivee = :heureArrivee
                            AND c.lieu_arrivee = :lieuArrivee";

            $stmtCovoit = $pdo->prepare($sqlCovoit);
            $stmtCovoit->execute([
                ':idUtilisateur' => $idUtilisateur,
                ':dateDepart' => $dateDepart,
                ':heureDepart' => $heureDepart,
                ':lieuDepart' => $depart,
                ':dateArrivee' => $dateArrivee,
                ':heureArrivee' => $heureArrivee,
                ':lieuArrivee' => $destination
            ]);

            $trajet = $stmtCovoit->fetch(PDO::FETCH_ASSOC);

            if ($trajet) {
                // L'utilisateur a déjà ajouté ce trajet
                $messageTrajet  = "Vous avez déjà proposé ce covoiturage.";
                $trajetValide = false;
                return;

                // Sinon, on insére le covoiturage et la relation participe
            } else {
                // 3. Ajouter le covoiturage
                $sqlAjoutTrajet = "INSERT INTO covoiturage (date_depart, heure_depart, lieu_depart, date_arrivee, heure_arrivee, lieu_arrivee, nb_place, prix_personne)
                                    VALUES (:dateDepart, :heureDepart, :lieuDepart, :dateArrivee, :heureArrivee, :lieuArrivee, :places, :prix)";
                $stmtAjoutTrajet = $pdo->prepare($sqlAjoutTrajet);
                $stmtAjoutTrajet->execute([
                    ':dateDepart' => $dateDepart,
                    ':heureDepart' => $heureDepart,
                    ':lieuDepart' => $depart,
                    ':dateArrivee' => $dateArrivee,
                    ':heureArrivee' => $heureArrivee,
                    ':lieuArrivee' => $destination,
                    ':places' => $places,
                    ':prix' => $prix
                ]);
                $idTrajet = $pdo->lastInsertId();

                // 4. Ajouter relation utilisateur-covoiturage dans participe
                $sqlParticipe = "INSERT INTO participe (utilisateur_utilisateur_id, covoiturage_covoiturage_id, chauffeur, passager)
                                VALUES (:idUtilisateur, :idCovoiturage, :chauffeur, :passager)";
                $stmtParticipe = $pdo->prepare($sqlParticipe);
                $stmtParticipe->execute([
                    ':idUtilisateur' => $idUtilisateur,
                    ':idCovoiturage' => $idTrajet,
                    ':chauffeur' => 1,
                    ':passager' => 0
                ]);

                // 5. Ajouter relation covoiturage–voiture dans utilise
                $sqlUtilise = "INSERT INTO utilise (voiture_voiture_id, covoiturage_covoiturage_id)
                            VALUES (:idVoiture, :idCovoiturage)";
                $stmtUtilise = $pdo->prepare($sqlUtilise);
                $stmtUtilise->execute([
                    ':idVoiture' => $voiture,
                    ':idCovoiturage' => $idTrajet
                ]);

                // 6. Déduire 2 crédits à l'utilisateur
                $prixTrajet = 2;
                $sqlRemoveCredits = "UPDATE utilisateur 
                                    SET credits = credits - ? 
                                    WHERE utilisateur_id = ?";
                $stmtRemoveCredits = $pdo->prepare($sqlRemoveCredits);
                $stmtRemoveCredits->execute([$prixTrajet, $idUtilisateur]);

                $trajetValide = true;
                $messageTrajet  = "covoiturage ajouté avec succès.";
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            $messageTrajet  = "Erreur lors de l’ajout : " . $e->getMessage();
        }
    }
}
