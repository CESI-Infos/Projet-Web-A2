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

    public function getAllRoles()
    {
        return $this->model->getAllRoles();
    }

    public function getRole($id)
    {
        return $this->model->getRole($id);
    }

    public function addRole()
    {
        $name = $_POST['NAME'];
        $this->model->createRole($name);

        header('Location: /');
        exit;
    }

    public function updateRole()
    {
        $id = (int)$_POST['ID'];
        $name = $_POST['NAME'];

        $this->model->updateRole($id, $name);

        header('Location: /');
        exit;
    }

    public function deleteRole()
    {
        $id = (int)$_POST['ID'];

        $this->model->deleteRole($id);

        header('Location: /');
        exit;
    }
}
