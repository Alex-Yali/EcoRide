<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../src/service/db.php'; // connexion PDO
require_once '../src/service/csrf.php';

$idUtilisateur = $_SESSION['user_id'] ?? null; // ID de la personne connectée
$compteSup = false;

if (!$idUtilisateur) {
    $messageSup  = "Erreur : aucun utilisateur connecté.";
    return;
}
try {
    $pdo->beginTransaction();

    // 1. Recuperer les comptes
    $sqlCompte = "SELECT u.utilisateur_id,
                        u.pseudo,
                        u.email,
                        r.libelle
                FROM utilisateur u
                JOIN possede p ON p.utilisateur_utilisateur_id = u.utilisateur_id
                JOIN role r ON r.role_id = p.role_role_id
                WHERE r.libelle IN ('utilisateur', 'employe')";
    $stmtCompte = $pdo->prepare($sqlCompte);
    $stmtCompte->execute();
    $compte = $stmtCompte->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['compte'])) {

        // Vérification CSRF
        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            throw new Exception("Erreur CSRF : requête invalide.");
        }

        // 2. Supprimer le role dans possede

        $idCompte = intval($_POST['compte']);;
        $sqlSupRole = "DELETE FROM possede WHERE utilisateur_utilisateur_id = :idUtilisateur";
        $stmtSupRole = $pdo->prepare($sqlSupRole);
        $stmtSupRole->execute([':idUtilisateur' => $idCompte]);

        // 3. Supprimer le compte dans utilisateur

        $sqlSupCompte = "DELETE FROM utilisateur WHERE utilisateur_id = :idUtilisateur";
        $stmtSupCompte = $pdo->prepare($sqlSupCompte);
        $stmtSupCompte->execute([':idUtilisateur' => $idCompte]);

        $pdo->commit();

        $messageSup  = "Compte supprimé avec succès.";
        $compteSup = true;
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    $messageSup  = "Erreur lors de l’ajout : " . $e->getMessage();
}
