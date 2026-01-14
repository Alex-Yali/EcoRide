<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../src/service/db.php'; // connexion PDO
require_once '../src/service/csrf.php';

$idUtilisateur = $_SESSION['user_id'] ?? null; // ID de la personne connectée
$voitureValide = false;
$voitureExiste = false;

if (!$idUtilisateur) {
    $messageVoiture  = "Erreur : aucun utilisateur connecté.";
    return;
}

if (!empty($_SESSION['user_id'])) {
    $sqlCheckVoiture = " SELECT 1
                        FROM gere
                        WHERE utilisateur_utilisateur_id = :id
                        LIMIT 1 ";
    $stmtCheckVoiture = $pdo->prepare($sqlCheckVoiture);
    $stmtCheckVoiture->execute([':id' => $_SESSION['user_id']]);
    $voitureExiste = $stmtCheckVoiture->fetchColumn() !== false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formType'] ?? '') === 'ajoutVoiture') {

    if ($voitureExiste) {
        $messageVoiture = "Vous avez déjà enregistré un véhicule.";
        $voitureValide = false;
        return;
    }

    // Vérification CSRF
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $messageVoiture = "Erreur CSRF : Requête invalide.";
        return;
    }

    $immat = trim($_POST['immatriculation'] ?? '');
    $dateImmat = trim($_POST['dateImmat'] ?? '');
    $modele = trim($_POST['modele'] ?? '');
    $couleur = trim($_POST['couleur'] ?? '');
    $marque = trim($_POST['marque'] ?? '');
    $energie = trim($_POST['energie'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $tabac = ucfirst(trim($_POST['tabac'] ?? ''));
    $animal = ucfirst(trim($_POST['animal'] ?? ''));
    $autre = ucfirst(trim($_POST['ajoutPref'] ?? ''));

    // Vérifier si un champ est vide
    if (
        $immat === '' || $dateImmat === '' || $modele === '' || $couleur === '' ||
        $marque === '' || $place === '' || $energie === '' || $tabac === '' || $animal === ''
    ) {
        $messageVoiture  = "Veuillez renseigner tous les champs.";
    } else {
        $modele = ucfirst(strtolower($modele));
        $marque = ucfirst(strtolower($marque));
        $couleur = strtolower($couleur);
        $energie = strtolower($energie);

        try {
            if (!$pdo->inTransaction()) {
                $pdo->beginTransaction();
            }

            // 1. Vérifier si la voiture existe déjà (par immatriculation)
            $sqlVoiture = "SELECT voiture_id FROM voiture WHERE immatriculation = :immat";
            $stmtVoiture = $pdo->prepare($sqlVoiture);
            $stmtVoiture->execute([':immat' => $immat]);
            $voiture = $stmtVoiture->fetch(PDO::FETCH_ASSOC);

            if ($voiture) {
                $idVoiture = $voiture['voiture_id'];
                $messageVoiture  = "Un véhicule avec cette immatriculation existe déjà.";
                // Autoriser l'accès au profil chauffeur
                $voitureValide = false;
            } else {
                // 2. Ajouter la voiture
                $sqlAjoutVoiture = "INSERT INTO voiture (modele, immatriculation, couleur, date_premiere_immatriculation, energie, nb_place)
                                    VALUES (:modele, :immat, :couleur, :dateImmat, :energie, :place)";
                $stmtAjoutVoiture = $pdo->prepare($sqlAjoutVoiture);
                $stmtAjoutVoiture->execute([
                    ':modele' => $modele,
                    ':immat' => $immat,
                    ':couleur' => $couleur,
                    ':dateImmat' => $dateImmat,
                    ':energie' => $energie,
                    ':place' => $place
                ]);
                $idVoiture = $pdo->lastInsertId();

                // 3. Vérifier ou ajouter la marque
                $sqlMarque = "SELECT marque_id FROM marque WHERE libelle = :marque";
                $stmtMarque = $pdo->prepare($sqlMarque);
                $stmtMarque->execute([':marque' => $marque]);
                $resultMarque = $stmtMarque->fetch(PDO::FETCH_ASSOC);

                if ($resultMarque) {
                    $idMarque = $resultMarque['marque_id'];
                } else {
                    $sqlAjoutMarque = "INSERT INTO marque (libelle) VALUES (:marque)";
                    $stmtAjoutMarque = $pdo->prepare($sqlAjoutMarque);
                    $stmtAjoutMarque->execute([':marque' => $marque]);
                    $idMarque = $pdo->lastInsertId();
                }

                // 4. Ajouter relation voiture–marque dans detient
                $sqlDetient = "INSERT INTO detient (voiture_voiture_id, marque_marque_id)
                                VALUES (:idVoiture, :idMarque)";
                $stmtDetient = $pdo->prepare($sqlDetient);
                $stmtDetient->execute([':idVoiture' => $idVoiture, ':idMarque' => $idMarque]);

                // 5. Ajouter relation utilisateur–voiture dans gere
                $sqlGere = "INSERT INTO gere (utilisateur_utilisateur_id, voiture_voiture_id)
                            VALUES (:idUtilisateur, :idVoiture)";
                $stmtGere = $pdo->prepare($sqlGere);
                $stmtGere->execute([':idUtilisateur' => $idUtilisateur, ':idVoiture' => $idVoiture]);

                $voitureValide = true;
                $messageVoiture  = "Véhicule ajouté avec succès.";
            }

            // Connexion MongoDB
            require_once 'mongo.php';

            $preferencesMongo = [
                "tabac" => $tabac,
                "animal" => $animal,
                "autre" => $autre
            ];

            // Sauvegarde ou mise à jour dans MongoDB
            $collectionPreferences->updateOne(
                ["utilisateur_id" => (int)$idUtilisateur],
                ['$set' => ["preferences" => $preferencesMongo]],
                ['upsert' => true]
            );

            $pdo->commit();
            header('Location: espace.php?voiture=ok');
            exit;
        } catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $messageVoiture = "Erreur lors de l’ajout : " . $e->getMessage();
        }
    }
}
