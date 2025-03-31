<?php
namespace App\Controllers;

require_once __DIR__.'/Controller.php';
require_once __DIR__.'/../Models/ApplicationModel.php';

use App\Controllers\Controller;
use App\Models\ApplicationModel;

class ApplicationController extends Controller
{
    public function __construct($templateEngine = null)
    {
        $this->model = new ApplicationModel();
        $this->templateEngine = $templateEngine;
    }

    public function getApplication(int $id): ?array
    {
        return $this->model->getApplication($id);
    }

    public function addApplication()
{
    // On récupère l'ID de l'offre, l'ID de l'utilisateur et les champs CV / Lettre
    $cv = $_POST['CV'] ?? '';
    $coverLetter = $_POST['LETTER'] ?? ''; // <-- nom cohérent avec le champ Twig
    $userId = $_SESSION['ID_USER'] ?? null;
    $offerId = $_POST['ID_OFFER'] ?? null;

    // Vérification basique
    if (!$userId || !$offerId || empty($cv) || empty($coverLetter)) {
        header("Location: ?uri=/details-offer&id=$offerId&error=1");
        exit;
    }

    // Appel au modèle pour insérer dans la BDD
    $this->model->addApplication($cv, $coverLetter, $userId, $offerId);

    // Redirection finale en cas de succès
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
