<?php
require "vendor/autoload.php";
require_once "src/Controllers/CompanyController.php";
require_once "src/Controllers/RatingController.php";
require_once "src/Controllers/OfferController.php";
require_once "src/Controllers/UserController.php";

use App\Controllers\CompanyController;
use App\Controllers\RatingController;
use App\Controllers\OfferController;
use App\Controllers\UserController;

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);

$CompanyController = new CompanyController($twig);
$OfferController = new OfferController($twig);
$UserController = new UserController($twig);
$RatingController = new RatingController();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_role = $_SESSION['id_role'] ?? null;
$firstname = $_SESSION['firstname'] ?? null;
$idUser = $_SESSION['idUser'] ?? null;

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
    if($_GET['action']=='rateCompany'){
        $RatingController->addNote(intval($_POST['idCompany']), intval($idUser), intval($_POST['rate']));
        header("Location: ?uri=/details-company&id=".$_POST['idCompany']);
    }
}

elseif (isset($_GET['uri'])) {
    $uri = $_GET['uri'];
}

switch ($uri) {
    case '/':
        $OfferController->printOffers('home.twig', 3);
        break;
    case '/browse':
        $OfferController->printOffers('browse.twig', 5);
        break;
    case '/details-offer':
        $offerId = $_GET['id'];
        $OfferController->printSpecificOffer($offerId);
        break;
    case '/create-offer':
        echo $twig->render('create-offer.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        break;
    case '/support':
        echo $twig->render('support.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        break;
    case '/connection':
        if(isset($firstname)){
            echo $twig->render('profile.twig',['firstname' => $firstname,'id_role' =>$id_role, 'isUser' => true]);
        }
        else{
            echo $twig->render('connection.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        }
        break;
    case '/connectionwrong':
        echo $twig->render('connection.twig',['firstname' => $firstname,'id_role' =>$id_role,'connected'=>"false"]);
        break;
    case '/sign-up':
        echo $twig->render('sign-up.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        break;
    case '/note':
        echo $twig->render('note.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        break;
    case '/details-company':
        $companyId = $_GET['id'];
        $CompanyController->printCompany($companyId, $firstname, $id_role);
        break;
    default:
        echo 'Page not found';
        break;
}