<?php
require_once '../src/service/db.php'; // connexion PDO
require_once '../src/service/csrf.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $message = '';
    $idUtilisateur = $_SESSION['user_id'] ?? null;

    $sqlRole = "SELECT passager, chauffeur FROM utilisateur WHERE utilisateur_id = ?";
    $stmtRole = $pdo->prepare($sqlRole);
    $stmtRole->execute([$idUtilisateur]);
    $role = $stmtRole->fetch(PDO::FETCH_ASSOC);

    $passager = (bool)$role['passager'];
    $chauffeur = (bool)$role['chauffeur'];

    if ($passager && !$chauffeur) {
        $radio = 'passager';
    } elseif (!$passager && $chauffeur) {
        $radio = 'chauffeur';
    } elseif ($passager && $chauffeur) {
        $radio = 'lesDeux';
    } else {
        $radio = '';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            throw new Exception("Erreur CSRF : requête invalide.");
        }

        $userRole = $_POST['user-role'] ?? null;

        if ($userRole) {
            if ($userRole === 'passager') {
                $passager = 1;
                $chauffeur = 0;
            } elseif ($userRole === 'chauffeur') {
                $passager = 0;
                $chauffeur = 1;
            } elseif ($userRole === 'lesDeux') {
                $passager = 1;
                $chauffeur = 1;
            }

            $sqlModifRole = "UPDATE utilisateur 
                            SET passager = ?, chauffeur = ?
                            WHERE utilisateur_id = ?";
            $stmtModifRole = $pdo->prepare($sqlModifRole);
            $stmtModifRole->execute([$passager, $chauffeur, $idUtilisateur]);

            $radio = $userRole;
        }
    }
} catch (Exception $e) {
    $message = "Une erreur est survenue : " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
