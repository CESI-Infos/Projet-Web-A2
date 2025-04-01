<?php
namespace App\Controllers;

require_once __DIR__.'/Controller.php';
require_once __DIR__.'/../Models/ApplicationModel.php';

use App\Controllers\Controller;
use App\Models\ApplicationModel;

class ApplicationController extends Controller
{
    public function __construct(/*$templateEngine = null*/)
    {
        $this->model = new ApplicationModel();
        //$this->templateEngine = $templateEngine;
    }

    public function getApplication(int $id): ?array
    {
        return $this->model->getApplication($id);
    }

    public function getAllOfferApply($idOffer){
        $applies = $this->model->getAllApplications();
        $compApply = [];
        foreach ($applies as $apply){
            if ($apply['ID_OFFER'] == $idOffer){
                $compApply[] = $idOffer;
            }
        }
        return $compApply;
    }

    public function addApplication()
    {
        session_start();
        $cv = $_POST['CV'] ?? '';
        $coverLetter = $_POST['COVER_LETTER'] ?? '';
        $userId = $_SESSION['ID_USER'] ?? null;
        $offerId = $_POST['ID_OFFER'] ?? null;

        if (!$userId || !$offerId || empty($cv) || empty($coverLetter)) {
            // Redirection avec erreur si un champ est manquant
            header("Location: ?uri=/details-offer&id=$offerId&error=1");
            exit;
        }

        $this->model->addApplication($cv, $coverLetter, $userId, $offerId);

        // Redirection avec succès vers la même page
        header("Location: ?uri=/details-offer&id=$offerId&success=1");
        exit;
    }

    public function updateApplication()
    {
        $id = (int)$_POST['ID'];
        $cv = $_POST['CV'] ?? '';
        $coverLetter = $_POST['COVER_LETTER'] ?? '';

        $this->model->updateApplication($id, $cv, $coverLetter);
        header('Location: /applications');
        exit;
    }

    public function deleteApplication()
    {
        $id = (int)$_POST['ID'];
        $this->model->deleteApplication($id);
        header('Location: /applications');
        exit;
    }

    public function __destruct() {}

    public function postuler()
    {
        session_start();
        $userId = (int) ($_POST['ID_USER'] ?? 0);
        $offerId = (int) ($_POST['ID_OFFER'] ?? 0);
        $cv = $_POST['CV'] ?? '';
        $coverLetter = $_POST['COVER_LETTER'] ?? '';

        if (!$userId || !$offerId || empty($cv) || empty($coverLetter)) {
            header("Location: ?uri=/details-offre&id=$offerId&error=1");
            exit;
        }

        $this->model->addApplication($cv, $coverLetter, $userId, $offerId);
        header("Location: ?uri=/details-offre&id=$offerId&success=1");
        exit;
    }
}
