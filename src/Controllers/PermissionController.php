<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/PermissionModel.php';
require_once __DIR__ . '/Controller.php';

use App\Models\PermissionModel;
use App\Controllers\Controller;

class PermissionController extends Controller {

    public function __construct($templateEngine) {
        $this->model = new PermissionModel();
        $this->templateEngine = $templateEngine;
    }

    public function addPermission() {
        $description = $_POST['DESCRIPTION'];
        $this->model->createPermission($description);
        header('Location: /');
        exit;
    }

    public function updatePermission() {
        $id = $_POST['ID'];
        $description = $_POST['DESCRIPTION'];
        $this->model->updatePermission($id, $description);
        header('Location: /');
        exit;
    }

    public function deletePermission() {
        $id = $_POST['ID'];
        $this->model->deletePermission($id);
        header('Location: /');
        exit;
    }

    public function getAllPermissions() {
        return $this->model->getAllPermissions();
    }

    public function getPermission($id) {
        return $this->model->getPermission($id);
    }
}
