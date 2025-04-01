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
    if (!isset($_FILES['CV']) || $_FILES['CV']['error'] !== UPLOAD_ERR_OK) {
        header("Location: ?uri=/details-offer&id=" . ($_POST['ID_OFFER'] ?? '') . "&error=1");
        exit;
    }

    $coverLetter = $_POST['LETTER'] ?? '';
    $userId = $_SESSION['idUser'] ?? null; 
    $offerId = $_POST['ID_OFFER'] ?? null;

    if (!$userId || !$offerId || empty($coverLetter)) {
        header("Location: ?uri=/details-offer&id=$offerId&error=1");
        exit;
    }

    $originalFileName = $_FILES['CV']['name'];
    $tmpFilePath = $_FILES['CV']['tmp_name'];

    $finalFileName = $originalFileName;

    $destination = __DIR__ . '/../../cv/' . $finalFileName;

    if (!move_uploaded_file($tmpFilePath, $destination)) {
        header("Location: ?uri=/details-offer&id=$offerId&error=1");
        exit;
    }

    $this->model->addApplication($finalFileName, $coverLetter, $userId, $offerId);

    header("Location: ?uri=/details-offer&id=$offerId&success=1");
    exit;
}


    public function updateApplication()
    {
        $id = (int)$_POST['ID'];
        $cv = $_POST['CV'] ?? '';
        $coverLetter = $_POST['LETTER'] ?? '';

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
}
