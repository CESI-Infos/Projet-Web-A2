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

$CompanyController = new CompanyController($twig);
$OfferController = new OfferController($twig);
$UserController = new UserController($twig);

$uri = '/';

if (isset($_GET['action']) && $_GET['action'] !== '') {
    if($_GET['action']==='authenticate'){
        $mail=$_POST["mail"];
        $password=$_POST["password"];
        $UserController->authenticate($mail,$password);

    }
    if($_GET['action']=='disconnect'){
        $UserController->disconnect();
    }
}

elseif (isset($_GET['uri'])) {
    $uri = $_GET['uri'];
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_role = $_SESSION['id_role'] ?? null;
$firstname = $_SESSION['firstname'] ?? null;

switch ($uri) {
    case '/':
        $OfferController->printOffers('accueil.twig', 3);
        break;
    case '/parcourir':
        $OfferController->printOffers('parcourir.twig', 5);
        break;
    case '/details-offre':
        $offerId = $_GET['id'];
        $OfferController->printSpecificOffer($offerId);
        break;
    case '/creation-offre':
        echo $twig->render('creation-offre.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        break;
    case '/support':
        echo $twig->render('support.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        break;
    case '/connexion':
        if(isset($firstname)){
            echo $twig->render('profile.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        }
        else{
            echo $twig->render('connexion.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        }
        break;
    case '/connexionwrong':
        echo $twig->render('connexion.twig',['firstname' => $firstname,'id_role' =>$id_role,'connected'=>"false"]);
        break;
    case '/inscription':
        echo $twig->render('inscription.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        break;
    default:
        echo 'Page not found';
        break;
}