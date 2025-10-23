<?php
session_start();

// Récupération sécurisée des données de session
$covoits = $_SESSION['covoits'] ?? [];
$date = $_SESSION['date'] ?? null;
// Nettoyage de la session 
unset($_SESSION['covoits'], $_SESSION['date']);

// Fonction pour formater la date
function formatDateFr(?string $dateStr): ?string {
    if (empty($dateStr)) return null;

    $jours = [
        'Sunday' => 'dimanche',
        'Monday' => 'lundi',
        'Tuesday' => 'mardi',
        'Wednesday' => 'mercredi',
        'Thursday' => 'jeudi',
        'Friday' => 'vendredi',
        'Saturday' => 'samedi'
    ];
    $mois = [
        1 => 'janvier', 2 => 'février', 3 => 'mars', 4 => 'avril', 5 => 'mai', 6 => 'juin',
        7 => 'juillet', 8 => 'août', 9 => 'septembre', 10 => 'octobre', 11 => 'novembre', 12 => 'décembre'
    ];

    $dt = DateTime::createFromFormat('Y-m-d', $dateStr);
    if (!$dt) return null;

    $jourSemaine = $jours[$dt->format('l')] ?? $dt->format('l');
    $jourNum = $dt->format('j');
    $moisNom = $mois[(int)$dt->format('n')] ?? $dt->format('F');
    $annee = $dt->format('Y');

    return ucfirst("$jourSemaine $jourNum $moisNom $annee");
}

$dateAffiche = formatDateFr($date);
?>