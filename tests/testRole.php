<?php

require_once __DIR__ . '/../src/Models/Database.php';
require_once __DIR__ . '/../src/Models/RoleModel.php';
require_once __DIR__ . '/../src/Controllers/RoleController.php';

use App\Controllers\RoleController;

$_POST = [];

$controller = new RoleController(null);

echo "=== AJOUT D'UN RÔLE ===\n";
$_POST['NAME'] = 'Rôle test';
$controller->addRole();
echo "-> OK\n";

echo "\n=== LISTE DES RÔLES ===\n";
$all = $controller->getAllRoles();
print_r($all);

$lastId = end($all)['ID'];

echo "\n=== MÀJ DU RÔLE ($lastId) ===\n";
$_POST['ID'] = $lastId;
$_POST['NAME'] = 'Rôle modifié';
$controller->updateRole();
echo "-> OK\n";

echo "\n=== LECTURE RÔLE ($lastId) ===\n";
print_r($controller->getRole($lastId));

echo "\n=== SUPPRESSION DU RÔLE ($lastId) ===\n";
$_POST['ID'] = $lastId;
$controller->deleteRole();
echo "-> OK\n";

echo "\n=== LISTE FINALE ===\n";
print_r($controller->getAllRoles());
