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

$CompanyController = new CompanyController($twig);
$OfferController = new OfferController($twig);
$UserController = new UserController($twig);

switch ($uri) {
    case '/':
        $OfferController->printOffers('acceuil.twig', 3);
        break;
    case '/parcourir':
        $OfferController->printOffers('parcourir.twig', 5);
        break;
    case '/details-offre':
        $offerId = $_GET['id'];
        $OfferController->printSpecificOffer($offerId);
        break;
    case '/creation-offre':
        echo $twig->render('creation-offre.twig');
        break;
    case '/support':
        echo $twig->render('support.twig');
        break;
    case '/connexion':
        echo $twig->render('connexion.twig');
        break;
    case '/inscription':
        echo $twig->render('inscription.twig');
        break;
    case '/mdp-oublie':
        echo $twig->render('mot-de-passe-oublie.twig');
        break;
    default:
        echo 'Page not found';
        break;
}