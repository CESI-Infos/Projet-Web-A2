<?php
require_once 'src/Models/ApplicationModel.php';
require_once 'src/Controllers/ApplicationController.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=thegoodplan", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $appController = new ApplicationController($pdo);

    echo "=== TEST CREATE APPLICATION ===\n";
    $newId = $appController->storeApplication(
        1,             
        5,               
        "cv/testcv.pdf",  
        "Voici ma lettre de motivation"
    );
    if ($newId !== false) {
        echo "-> Candidature créée avec l'ID : $newId\n";
    } else {
        echo "-> Échec de la création\n";
    }

    echo "\n=== TEST LIST APPLICATIONS ===\n";
    $allApps = $appController->listApplications();
    print_r($allApps);

    echo "\n=== TEST SHOW APPLICATION (ID = $newId) ===\n";
    $application = $appController->showApplication($newId);
    print_r($application);

    echo "\n=== TEST UPDATE APPLICATION (ID = $newId) ===\n";
    $updateResult = $appController->updateApplication(
        $newId,             
        1,                  
        5,                  
        "cv/updatedcv.pdf",
        "Lettre mise à jour"
    );
    echo $updateResult ? "-> Mise à jour OK\n" : "-> Échec de la mise à jour\n";

    echo "\n=== TEST DELETE APPLICATION (ID = $newId) ===\n";
    $deleteResult = $appController->destroyApplication($newId);
    echo $deleteResult ? "-> Suppression OK\n" : "-> Échec de la suppression\n";

} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage() . "\n";
} catch (Exception $ex) {
    echo "Erreur générale : " . $ex->getMessage() . "\n";
}

