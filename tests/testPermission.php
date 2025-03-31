<?php

require_once '../src/Models/PermissionModel.php';
require_once '../src/Controllers/PermissionController.php';

use App\Controllers\PermissionController;

try {
    $controller = new PermissionController(null);

    echo "=== AJOUT D'UNE PERMISSION ===\n";
    $_POST['DESCRIPTION'] = 'Analyser les rapports';
    $controller->addPermission();
    echo "-> Ajout OK\n";

    echo "\n=== LISTE DES PERMISSIONS ===\n";
    $all = $controller->getAllPermissions();
    print_r($all);

    $lastId = end($all)['ID'];

    echo "\n=== AFFICHAGE DE LA PERMISSION ($lastId) ===\n";
    $one = $controller->getPermission($lastId);
    print_r($one);

    echo "\n=== MODIFICATION DE LA PERMISSION ($lastId) ===\n";
    $_POST['ID'] = $lastId;
    $_POST['DESCRIPTION'] = 'Analyser les performances système';
    $controller->updatePermission();
    echo "-> Modification OK\n";

    echo "\n=== PERMISSION MISE À JOUR ===\n";
    print_r($controller->getPermission($lastId));

    echo "\n=== SUPPRESSION DE LA PERMISSION ($lastId) ===\n";
    $_POST['ID'] = $lastId;
    $controller->deletePermission();
    echo "-> Suppression OK\n";

    echo "\n=== LISTE FINALE ===\n";
    print_r($controller->getAllPermissions());

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
