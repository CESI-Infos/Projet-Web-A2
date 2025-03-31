<?php

require_once 'src/Models/ApplicationModel.php';
require_once 'src/Controllers/ApplicationController.php';

use App\Models\ApplicationModel;
use App\Controllers\ApplicationController;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=thegoodplan", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $controller = new ApplicationController();
    $model = new ApplicationModel($pdo);

    echo "=== TEST AJOUT DE CANDIDATURE ===\n";
    $userId = 1;     
    $offerId = 2;    
    $cv = "cv_test.pdf";
    $coverLetter = "Lettre de motivation test";

    $newId = $model->addApplication($cv, $coverLetter, $userId, $offerId);
    echo "→ Nouvelle candidature ajoutée avec l'ID : $newId\n";

    echo "\n=== TEST AFFICHAGE DE TOUTES LES CANDIDATURES ===\n";
    $apps = $model->getAllApplications();
    print_r($apps);

    echo "\n=== TEST AFFICHAGE DE LA CANDIDATURE ID = $newId ===\n";
    $app = $model->getApplication($newId);
    print_r($app);

    echo "\n=== TEST MISE À JOUR DE LA CANDIDATURE ===\n";
    $cv = "cv_modifié.pdf";
    $coverLetter = "Lettre modifiée";
    $model->updateApplication($newId, $cv, $coverLetter);
    echo "→ Candidature mise à jour.\n";

    echo "\n=== TEST SUPPRESSION ===\n";
    $model->deleteApplication($newId);
    echo "→ Candidature supprimée.\n";

} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
