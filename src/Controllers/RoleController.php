<?php
namespace App\Controllers;

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../Models/RoleModel.php';

use App\Controllers\Controller;
use App\Models\RoleModel;

class RoleController extends Controller
{
    public function __construct($templateEngine)
    {
        $this->model = new RoleModel();
        $this->templateEngine = $templateEngine;
    }
    // Retrieves all roles
    public function getAllRoles()
    {
        return $this->model->getAllRoles();
    }
    // Retrieves a role by its ID
    public function getRole($id)
    {
        return $this->model->getRole($id);
    }
    // Adds a role
    public function addRole()
    {
        $name = $_POST['NAME'];
        $this->model->createRole($name);

        header('Location: /');
        exit;
    }
    // Edits a role
    public function updateRole()
    {
        $id = (int)$_POST['ID'];
        $name = $_POST['NAME'];

        $this->model->updateRole($id, $name);

        header('Location: /');
        exit;
    }
    // Deletes a role
    public function deleteRole()
    {
        $id = (int)$_POST['ID'];

        $this->model->deleteRole($id);

        header('Location: /');
        exit;
    }
}
