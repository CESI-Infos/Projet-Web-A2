<?php
require "vendor/autoload.php";
require_once "src/Controllers/CompanyController.php";
require_once "src/Controllers/OfferController.php";
require_once "src/Controllers/UserController.php";

use App\Controllers\CompanyController;
use App\Controllers\OfferController;
use App\Controllers\UserController;

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);

if (isset($_GET['uri'])) {
    $uri = $_GET['uri'];
} else {
    $uri = '/';
}

$uriPath = strtok($uri, '?');

$CompanyController = new CompanyController($twig);
$OfferController = new OfferController($twig);
$UserController = new UserController($twig);

switch ($uriPath) {
    case '/':
        $OfferController->printOffers('acceuil.html', 3);
        break;
    case '/parcourir':
        $OfferController->printOffers('parcourir.html', 5);
        break;
    case '/creation-offre':
        echo $twig->render('creation-offre.html');
        break;
    case '/support':
        echo $twig->render('support.html');
        break;
    default:
        echo 'Page not found';
        break;
}