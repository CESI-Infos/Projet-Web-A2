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
    // Adds a permission
    public function addPermission() {
        $description = $_POST['DESCRIPTION'];
        $this->model->createPermission($description);
        header('Location: /');
        exit;
    }
    // Edit a permission
    public function updatePermission() {
        $id = $_POST['ID'];
        $description = $_POST['DESCRIPTION'];
        $this->model->updatePermission($id, $description);
        header('Location: /');
        exit;
    }
    // Delete a permission
    public function deletePermission() {
        $id = $_POST['ID'];
        $this->model->deletePermission($id);
        header('Location: /');
        exit;
    }
    // Retrieves all permissions
    public function getAllPermissions() {
        return $this->model->getAllPermissions();
    }
    // Retrieves a permission by its ID
    public function getPermission($id) {
        return $this->model->getPermission($id);
    }
}
