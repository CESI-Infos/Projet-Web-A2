<?php
namespace App\Controllers;

require_once 'src/Controllers/Controller.php';
require_once 'src/Models/ApplicationModel.php';

use App\Controllers\Controller;
use App\Models\ApplicationModel;

class ApplicationController extends Controller
{
    protected ApplicationModel $model;

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
        session_start();
        $cv = $_POST['CV'] ?? '';
        $coverLetter = $_POST['COVER_LETTER'] ?? '';
        $userId = $_SESSION['user_id'] ?? null;
        $offerId = $_POST['OFFER_ID'] ?? null;

        if (!$userId || !$offerId) {
            header('Location: /offers?error=missing');
            exit;
        }

        $this->model->addApplication($cv, $coverLetter, $userId, $offerId);
        header('Location: /offers?success=1');
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
}
