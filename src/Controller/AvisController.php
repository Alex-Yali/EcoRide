<?php

namespace App\Controller;

use App\db\Mysql;
use App\Service\AvisServices;

class AvisController extends Controller
{
    /* ============================================ Gestion des avis ============================================= */

    public function avis(): void
    {
        try {
            $message = "";
            $csrf = generate_csrf_token();
            $avis = [];
            $infosCovoitAvis = [];
            $idUtilisateur = $_SESSION['user_id'] ?? null;

            $avisServices = new AvisServices;

            // Récupération des avis
            $avis = $avisServices->avis();

            if (empty($avis)) {
                $message = "Aucun avis à gérer.";
            }

            // Récupérer les infos du voyage de l'avis à traiter
            $idAvis = $_GET['avis_id'] ?? null;
            if ($idAvis) {
                $infosCovoitAvis = $avisServices->infosCovoitAvis($idAvis);
            }

            // Vérifier POST + CSRF
            if ($_SERVER["REQUEST_METHOD"] === "POST") {

                if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
                    $message = "Erreur CSRF : requête invalide.";
                } else {
                    // Valider avis
                    if (isset($_POST['valider'])) {

                        $idAvis = $_POST['valider'];

                        $avisServices->validerAvis($idAvis, $idUtilisateur);

                        header("Location: /avisEnCours/");
                        exit;
                    }

                    // Refuser l'avis
                    if (isset($_POST['refuser'])) {

                        $idAvis = $_POST['refuser'];

                        $avisServices->refuserAvis($idAvis, $idUtilisateur);

                        header("Location: /avisEnCours/");
                        exit;
                    }
                }
            }
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }
        // Afficher la vue
        $this->render("pages/avisEnCours", [
            'avis' => $avis,
            'infosCovoitAvis' => $infosCovoitAvis,
            'message' => $message,
            'csrf' => $csrf ?? '',
        ]);
    }

    /* ============================================ Historique avis ============================================= */
    public function historiqueAvis(): void
    {
        try {
            $message = "";
            $idUtilisateur = $_SESSION['user_id'] ?? null;

            $avisServices = new AvisServices;

            // Récupération historique des avis
            $avisCheck = $avisServices->historiqueAvis($idUtilisateur);
        } catch (\Exception $e) {
            $message = "Une erreur est survenue : " . $e->getMessage();
        }
        // Afficher la vue
        $this->render("pages/historiqueAvis", [
            'avisCheck' => $avisCheck,
            'message' => $message,
        ]);
    }
}
