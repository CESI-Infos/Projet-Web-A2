<?php

require_once 'src/Models/RatingModel.php';
require_once 'src/Controllers/RatingController.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=thegoodplan", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $ratingModel = new RatingModel($pdo);
    $ratingCtrl  = new RatingController($ratingModel);

    echo "=== TEST ADD NOTE ===\n";
    $ratingCtrl->addNote(10, 4, 5);

    echo "\n=== TEST GET NOTE (Company=10, User=4) ===\n";
    $noteVal = $ratingCtrl->getNote(10, 4);
    echo "-> note = $noteVal\n";

    echo "\n=== TEST UPDATE NOTE (Company=10, User=4) ===\n";
    $ratingCtrl->addNote(10, 4, 9);
    $noteVal = $ratingCtrl->getNote(10, 4);
    echo "-> nouvelle note = $noteVal\n";

    echo "\n=== TEST DELETE NOTE (Company=10, User=4) ===\n";
    $ratingCtrl->deleteNote(10, 4);
    $noteVal = $ratingCtrl->getNote(10, 4);
    echo "-> après suppression, note = $noteVal (attendu = -1)\n";

} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
} catch (Exception $ex) {
    echo "Erreur générale : " . $ex->getMessage();
}
