<?php
namespace App\Controllers;

require_once 'Controller.php';
require_once '../src/Models/ApplicationModel.php';

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
        session_start();
        $cv = $_POST['CV'] ?? '';
        $coverLetter = $_POST['LETTER'] ?? '';
        $userId = $_SESSION['ID_USER'] ?? null;
        $offerId = $_POST['ID_OFFER'] ?? null;

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
