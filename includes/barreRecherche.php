<?php
require_once 'back/db.php';

$message = "";

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $depart = trim($_POST['depart']);
    $arrivee = trim($_POST['arrivee']);
    $date = trim($_POST['date']);

    if (!empty($depart) && !empty($arrivee) && !empty($date)) {
        // Requête préparée pour éviter les injections SQL
        $stmt = $pdo->prepare("SELECT * FROM covoiturage 
                               WHERE lieu_depart = ? 
                               AND lieu_arrivee = ? 
                               AND date_depart = ?");
        $stmt->execute([$depart, $arrivee, $date]);
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultats) {
            // Stocke les résultats dans la session
            $_SESSION['covoits'] = $resultats;
            $_SESSION['depart'] = $depart;
            $_SESSION['arrivee'] = $arrivee;
            $_SESSION['date'] = $date;
            header('Location: covoiturage.php'); // redirection
            exit;
        } else {
            $message = "Aucun covoiturage trouvé pour cette recherche.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
$depart_value = isset($_SESSION['depart']) ? $_SESSION['depart'] : '';
$arrivee_value = isset($_SESSION['arrivee']) ? $_SESSION['arrivee'] : '';
$date_value = isset($_SESSION['date']) ? $_SESSION['date'] : '';
?>

<form class="nav-bar" action="" method="POST">
            <!-- Départ -->
    <section class="nav-choix">
        <img src="./assets/images/Cars 1.png" class="icon" alt="image voiture">
        <input type="text" name="depart" id="depart" placeholder="Ville départ" value="<?= htmlspecialchars($depart_value) ?>" required>
        <section class="separateur"></section>
    </section>
            <!-- Destination -->
    <section class="nav-choix">
        <img src="./assets/images/ping.png" class="icon" alt="image destination">
        <input type="text" name="arrivee" id="destination" placeholder="Ville d'arrivée" value="<?= htmlspecialchars($arrivee_value) ?>" required>
        <section class="separateur"></section>
    </section>
            <!-- Calendrier -->
    <section class="nav-choix">
        <img src="./assets/images/calendrier gris.png" class="icon" alt="image calendrier" >
        <input id="date" type="date" name="date" require>
    </section>
            <!-- Bouton -->
        <button id="btnNav" type="submit">Rechercher</button> 
</form>