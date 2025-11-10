<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; // connexion PDO

global $message;
$idUtilisateur = $_SESSION['user_id'] ?? null; // ID de la personne connectée
$voitureValide = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formType'] ?? '') === 'ajoutVoiture') {
    $immat = trim($_POST['immatriculation'] ?? '');
    $dateImmat = trim($_POST['dateImmat'] ?? '');
    $modele = trim($_POST['modele'] ?? '');
    $couleur = trim($_POST['couleur'] ?? '');
    $marque = trim($_POST['marque'] ?? '');
    $energie = trim($_POST['energie'] ?? '');
    $place = trim($_POST['place'] ?? '');
    $tabac = trim($_POST['tabac'] ?? '');
    $animal = trim($_POST['animal'] ?? '');
    $ajoutPref = trim($_POST['ajoutPref'] ?? '');

    // Vérifier si un champ est vide
    if (
        $immat === '' || $dateImmat === '' || $modele === '' ||
        $couleur === '' || $marque === '' || $place === '' ||
        $energie === '' || $tabac === '' || $animal === '' 
    ) {
        $message = "Veuillez renseigner tous les champs.";
    } else {

        // Formatage automatique
        $modele = ucfirst(strtolower($modele));
        $marque = ucfirst(strtolower($marque));
        $couleur = strtolower($couleur);
        $energie = strtolower($energie);

        try {
            //  Démarre une transaction pour garantir la cohérence
            $pdo->beginTransaction();

            // Vérifier si le véhicule existe déjà (par immatriculation)
            $sqlVoiture = "SELECT voiture_id FROM voiture WHERE immatriculation = :immat";
            $stmtVoiture = $pdo->prepare($sqlVoiture);
            $stmtVoiture->execute([':immat' => $immat]);
            $voiture = $stmtVoiture->fetch(PDO::FETCH_ASSOC);

            if ($voiture) {
                $idVoiture = $voiture['voiture_id'];
                $message = "Un véhicule avec cette immatriculation existe déjà.";
            } else {
                //  1. Insertion de la voiture
                $sqlAjoutVoiture = "INSERT INTO voiture 
                    (modele, immatriculation, couleur, date_premiere_immatriculation, energie, nb_place)
                    VALUES (:modele, :immat, :couleur, :dateImmat, :energie, :place)";
                $stmtAjoutVoiture = $pdo->prepare($sqlAjoutVoiture);
                $stmtAjoutVoiture->execute([
                    ':modele' => $modele,
                    ':immat' => $immat,
                    ':couleur' => $couleur,
                    ':dateImmat' => $dateImmat,
                    ':energie' => $energie,
                    ':place' => $place,
                ]);
                $idVoiture = $pdo->lastInsertId();
            }

            //  2. Vérifier si la marque existe déjà
            $sqlMarque = "SELECT marque_id FROM marque WHERE libelle = :marque";
            $stmtMarque = $pdo->prepare($sqlMarque);
            $stmtMarque->execute([':marque' => $marque]);
            $resultMarque = $stmtMarque->fetch(PDO::FETCH_ASSOC);

            if ($resultMarque) {
                $idMarque = $resultMarque['marque_id'];
            } else {
                // Si la marque n’existe pas, on l’ajoute
                $sqlAjoutMarque = "INSERT INTO marque (libelle) VALUES (:marque)";
                $stmtAjoutMarque = $pdo->prepare($sqlAjoutMarque);
                $stmtAjoutMarque->execute([':marque' => $marque]);
                $idMarque = $pdo->lastInsertId();
            }

            //  3. Ajouter la relation voiture-marque (table detient)
            $stmtDetient = $pdo->prepare("
                INSERT INTO detient (voiture_voiture_id, marque_marque_id)
                VALUES (:idVoiture, :idMarque)
            ");
            $stmtDetient->execute([':idVoiture' => $idVoiture,':idMarque' => $idMarque]);

            //  4. Ajouter la relation utilisateur-voiture (table gere)
            $stmtGere = $pdo->prepare("
                INSERT INTO gere (utilisateur_utilisateur_id, voiture_voiture_id)
                VALUES (:idUtilisateur, :idVoiture)
            ");
            $stmtGere->execute([':idUtilisateur' => $idUtilisateur, ':idVoiture' => $idVoiture]);

            //  5. Vérifier si la preference existe déjà
            $preferences = [$tabac, $animal, $ajoutPref];
            foreach ($preferences as $pref) {
                if ($pref !== '') {
                    $stmt = $pdo->prepare("SELECT preference_id FROM preference WHERE libelle = :pref");
                    $stmt->execute([':pref' => $pref]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!$result) {
                        $stmtInsert = $pdo->prepare("INSERT INTO preference (libelle) VALUES (:pref)");
                        $stmtInsert->execute([':pref' => $pref]);
                        $idPref = $pdo->lastInsertId();
                    } else {
                        $idPref = $result['preference_id'];
                    }
                    // Relation utilisateur–préférence
                    $stmtFournir = $pdo->prepare("
                        INSERT INTO fournir (utilisateur_utilisateur_id, preference_preference_id)
                        VALUES (:idUtilisateur, :idPref)
                    ");
                    $stmtFournir->execute([':idUtilisateur' => $idUtilisateur, ':idPref' => $idPref]);
                }
            }
            ;

            //  Valider la transaction
            $pdo->commit();

            $message = "Véhicule ajouté avec succès.";
            $voitureValide = true;

        } catch (PDOException $e) {
            // En cas d’erreur, on annule la transaction
            $pdo->rollBack();
            $message = "Erreur lors de l’ajout : " . $e->getMessage();
        }
    }
}
?>
