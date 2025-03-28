<?php
require_once 'src/Models/PermissionModel.php';
require_once 'src/Controllers/PermissionController.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=thegoodplan", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $permissionController = new PermissionController($pdo);

    echo "=== TEST CREATE PERMISSION ===\n";
    $newId = $permissionController->storePermission("Permission de Test", "Une description de test");
    if ($newId !== false) {
        echo "-> Permission créée avec l'ID : $newId\n";
    } else {
        echo "-> Échec de la création\n";
    }

    echo "\n=== TEST LIST PERMISSIONS ===\n";
    $allPermissions = $permissionController->listPermissions();
    print_r($allPermissions);

    echo "\n=== TEST SHOW PERMISSION (ID = $newId) ===\n";
    $permission = $permissionController->showPermission($newId);
    print_r($permission);

    echo "\n=== TEST UPDATE PERMISSION (ID = $newId) ===\n";
    $updateResult = $permissionController->updatePermission($newId, "Permission Modifiée", "Description modifiée");
    echo $updateResult ? "-> Mise à jour OK\n" : "-> Échec de la mise à jour\n";

    echo "\n=== TEST DELETE PERMISSION (ID = $newId) ===\n";
    $deleteResult = $permissionController->destroyPermission($newId);
    echo $deleteResult ? "-> Suppression OK\n" : "-> Échec de la suppression\n";

} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage() . "\n";
} catch (Exception $ex) {
    echo "Erreur générale : " . $ex->getMessage() . "\n";
}
