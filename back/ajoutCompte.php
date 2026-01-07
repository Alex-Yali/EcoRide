<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; // connexion PDO
require_once 'csrf.php';

$idUtilisateur = $_SESSION['user_id'] ?? null; // ID de la personne connectée
$compteValide = false;

if (!$idUtilisateur) {
    $messageCompte  = "Erreur : aucun utilisateur connecté.";
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['formType'] ?? '') === 'ajoutCompte') {

        // Vérification CSRF
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $messageCompte = "Erreur CSRF : requête invalide.";
        return;
    }

    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $pseudo = trim($_POST['pseudo'] ?? '');
    $credits = (int)$_POST['credits'];

    // Vérifier si un champ est vide
    if ($email === '' || $password === '' || $pseudo === '' || $credits <= 0 ) {
        $messageCompte  = "Veuillez renseigner tous les champs.";

        //Vérifier si le mot de passe respect notre demande
    }elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
        $messageCompte = "Le mot de passe doit contenir au moins 9 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";

    } else {
        $pseudo = ucfirst(strtolower($pseudo));
        $email = strtolower($email);

        try {
            $pdo->beginTransaction();

            // 1. Vérifier si le compte existe déjà (par email)
            $sqlCompte = "SELECT email FROM utilisateur WHERE email = :email";
            $stmtCompte = $pdo->prepare($sqlCompte);
            $stmtCompte->execute([':email' => $email]);
            $compte = $stmtCompte->fetch(PDO::FETCH_ASSOC);

            if ($compte) {
                $messageCompte  = "Un utilisateur avec cette adresse email existe déjà.";
                $compteValide = false;
            } else {

            // Hashage du mot de passe en utilisant BCRYPT
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // 2. Ajouter le compte
            $sqlAjoutCompte = "INSERT INTO utilisateur (email, password, pseudo, credits)
                                VALUES (:email, :password, :pseudo, :credits)";
            $stmtAjoutCompte = $pdo->prepare($sqlAjoutCompte);
            $stmtAjoutCompte->execute([
                ':email' => $email,
                ':password' => $hashedPassword,
                ':pseudo' => $pseudo,
                ':credits' => $credits
            ]);
            $idUtilisateur = $pdo->lastInsertId();

            // 3. Vérifier ou ajouter le role
            $role = "employe";
            $sqlRole = "SELECT role_id FROM role WHERE libelle = :role";
            $stmtRole = $pdo->prepare($sqlRole);
            $stmtRole->execute([':role' => $role]);
            $resultRole = $stmtRole->fetch(PDO::FETCH_ASSOC);

            if ($resultRole) {
                $idRole = $resultRole['role_id'];
            } else {
                $sqlAjoutRole = "INSERT INTO role (libelle) VALUES (:role)";
                $stmtAjoutRole = $pdo->prepare($sqlAjoutRole);
                $stmtAjoutRole->execute([':role' => $role]);
                $idRole = $pdo->lastInsertId();
            }

            // 4. Ajouter relation utilisateur-role dans possede
            $sqlPossede = "INSERT INTO possede (utilisateur_utilisateur_id, role_role_id)
                            VALUES (:idUtilisateur, :idRole)";
            $stmtPossede = $pdo->prepare($sqlPossede);
            $stmtPossede->execute([
                ':idUtilisateur' => $idUtilisateur,
                ':idRole' => $idRole
                ]);

            $messageCompte  = "Compte ajouté avec succès.";
            $compteValide = true;
        }

        $pdo->commit();

        } catch (PDOException $e) {
            $pdo->rollBack();
            $messageCompte  = "Erreur lors de l’ajout : " . $e->getMessage();
            error_log("Erreur ajout compte : " . $e->getMessage());
        }
    }
}
?>
