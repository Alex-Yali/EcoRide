<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php'; // connexion PDO

$idUtilisateur = $_SESSION['user_id'] ?? null;

if (!$idUtilisateur) {
    $messageCompte  = "Erreur : aucun utilisateur connecté.";
    return;
}

// -- Graphique 1 -- //

    // 1-Récupérer les covoiturages par date
    $sqlCovoitDate = " SELECT date_depart, COUNT(*) AS total
                    FROM covoiturage
                    GROUP BY date_depart
                    ORDER BY date_depart ASC";
    $stmtCovoitDate = $pdo->prepare($sqlCovoitDate);
    $stmtCovoitDate->execute();

    $data = $stmtCovoitDate->fetchAll(PDO::FETCH_ASSOC);

    // 2-Préparer les données pour Chart.js
    $date = [];
    $total = [];

    foreach ($data as $row) {
        $date[] = $row['date_depart'];
        $total[] = (int)$row['total'];
    }

// -- Graphique 2 -- //

    // 1-Récupérer les crédits par covoit
    $sqlCovoitCredit = " SELECT date_depart, COUNT(*) * 2 AS totalCredit
                    FROM covoiturage
                    GROUP BY date_depart
                    ORDER BY date_depart ASC";
    $stmtCovoitCredit = $pdo->prepare($sqlCovoitCredit);
    $stmtCovoitCredit->execute();

    $data2 = $stmtCovoitCredit->fetchAll(PDO::FETCH_ASSOC);

    // 2-Préparer les données pour Chart.js
    $date2 = [];
    $totalCredit = [];

    foreach ($data2 as $row) {
        $date2[] = $row['date_depart'];
        $totalCredit[] = (int)$row['totalCredit'];
    }

// -- Total crédits -- //
    $sqlTotalCredit = "SELECT COUNT(*) * 2 AS totalCredits FROM covoiturage";
    $stmtTotalCredit = $pdo->prepare($sqlTotalCredit);
    $stmtTotalCredit->execute();
    $totalCredits = $stmtTotalCredit->fetch(PDO::FETCH_ASSOC);
?>
