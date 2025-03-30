<?php

require_once 'src/Models/RoleModel.php';
require_once 'src/Controllers/RoleController.php';

try {
    $file = __DIR__ . '/storage/roles.json';
    $roleModel = new RoleModel($file);

    $roleController = new RoleController($roleModel);

    echo "=== TEST CREATE ROLE ===\n";
    $roleController->createRole("admin");
    $roleController->createRole("manager");
    $roleController->createRole("student");

    echo "\n=== TEST LIST ROLES ===\n";
    $roles = $roleController->listRoles();
    print_r($roles);

    echo "\n=== TEST DELETE ROLE 'manager' ===\n";
    $roleController->deleteRole("manager");

    echo "\n=== TEST LIST ROLES (after deletion) ===\n";
    $roles = $roleController->listRoles();
    print_r($roles);

    echo "\n=== TEST roleExists('admin') ===\n";
    $exists = $roleModel->roleExists("admin");
    echo $exists ? "-> 'admin' existe\n" : "-> 'admin' n'existe pas\n";

} catch (Exception $ex) {
    echo "Erreur générale : " . $ex->getMessage();
}
