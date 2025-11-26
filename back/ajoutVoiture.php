<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; // connexion PDO

$idUtilisateur = $_SESSION['user_id'] ?? null; // ID de la personne connectée
$voitureValide = false;

if (!$idUtilisateur) {
    $messageVoiture  = "Erreur : aucun utilisateur connecté.";
    return;
}

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
    if ($immat === '' || $dateImmat === '' || $modele === '' || $couleur === '' ||
        $marque === '' || $place === '' || $energie === '' || $tabac === '' || $animal === '') {
        $messageVoiture  = "Veuillez renseigner tous les champs.";
    } else {
        $modele = ucfirst(strtolower($modele));
        $marque = ucfirst(strtolower($marque));
        $couleur = strtolower($couleur);
        $energie = strtolower($energie);

        try {
            $pdo->beginTransaction();

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

                // 6. Ajouter préférences (dédupliquer pour éviter doublons)
                $preferences = array_unique([$tabac, $animal, $ajoutPref]);

                foreach ($preferences as $pref) {
                    if ($pref !== '') {
                        // Vérifier si la préférence existe
                        $sqlPref = "SELECT preference_id FROM preference WHERE libelle = :pref";
                        $stmtPref = $pdo->prepare($sqlPref);
                        $stmtPref->execute([':pref' => $pref]);
                        $resultPref = $stmtPref->fetch(PDO::FETCH_ASSOC);
                        if (!$resultPref) {
                            $sqlInsertPref = "INSERT INTO preference (libelle) VALUES (:pref)";
                            $stmtInsertPref = $pdo->prepare($sqlInsertPref);
                            $stmtInsertPref->execute([':pref' => $pref]);
                            $idPref = $pdo->lastInsertId();
                        } else {
                            $idPref = $resultPref['preference_id'];
                        }

                        // Vérifier si la relation utilisateur–préférence existe déjà
                        $sqlCheckPref = "SELECT 1 FROM fournir 
                                        WHERE utilisateur_utilisateur_id = :idUtilisateur 
                                        AND preference_preference_id = :idPref";
                        $stmtCheckPref = $pdo->prepare($sqlCheckPref);
                        $stmtCheckPref->execute([':idUtilisateur' => $idUtilisateur, ':idPref' => $idPref]);
                        $existPref = $stmtCheckPref->fetchColumn();

                        if (!$existPref) {
                            $sqlFournir = "INSERT INTO fournir (utilisateur_utilisateur_id, preference_preference_id)
                                            VALUES (:idUtilisateur, :idPref)";
                            $stmtFournir = $pdo->prepare($sqlFournir);
                            $stmtFournir->execute([':idUtilisateur' => $idUtilisateur, ':idPref' => $idPref]);
                        }
                    }
                }

                $voitureValide = true;
                $messageVoiture  = "Véhicule ajouté avec succès.";
            }

            $pdo->commit();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['form_data'] = [
        'immat' => $_POST['immatriculation'] ?? '',
        'dateImmat' => $_POST['dateImmat'] ?? '',
        'modele' => $_POST['modele'] ?? '',
        'couleur' => $_POST['couleur'] ?? '',
        'marque' => $_POST['marque'] ?? '',
        'energie' => $_POST['energie'] ?? '',
        'place' => $_POST['place'] ?? ''
    ];
    $_SESSION['form_submitted'] = true; // Formulaire envoyé
}

        } catch (PDOException $e) {
            $pdo->rollBack();
            $messageVoiture  = "Erreur lors de l’ajout : " . $e->getMessage();
        }
    }
}
?>
