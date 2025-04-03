<?php
//index.php

/* Adding and initializing all files */
require "vendor/autoload.php";
require_once "src/Controllers/CompanyController.php";
require_once "src/Controllers/RatingController.php";
require_once "src/Controllers/OfferController.php";
require_once "src/Controllers/UserController.php";
require_once "src/Controllers/ApplicationController.php";
require_once "src/Controllers/WishListController.php";

use App\Controllers\CompanyController;
use App\Controllers\RatingController;
use App\Controllers\OfferController;
use App\Controllers\UserController;
use App\Controllers\ApplicationController;
use App\Controllers\WishlistController;

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, [
    'debug' => true
]);

$CompanyController = new CompanyController($twig);
$OfferController = new OfferController($twig);
$UserController = new UserController($twig);
$RatingController = new RatingController();
$ApplicationController = new ApplicationController($twig);
$WishlistController = new WishlistController($twig);
/**/

/* Management of the session when we arrive on the index page as well as in the authenticate method of UserController */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_role = $_SESSION['id_role'] ?? null;
$firstname = $_SESSION['firstname'] ?? null;
$idUser = $_SESSION['idUser'] ?? null;
/**/

$uri = '/';

$keywords = null;
$duration = null;
$experience = null;

/* Action taken when validating a form */
if (isset($_GET['action']) && $_GET['action'] !== '') {
    if ($_GET['action'] === 'authenticate') {
        $mail = $_POST["mail"];
        $password = $_POST["password"];
        $UserController->authenticate($mail, $password);
    }
    if ($_GET['action'] == 'disconnect') {
        $UserController->disconnect();
    }
    if ($_GET['action'] == 'filteroffers'){
        $keywords = $_POST['keywords'] ?? null;
        $duration = $_POST['duration'] ?? null;
        $experience = $_POST['experience'] ?? null;

        $uri='/browse';
    }
    if($_GET['action'] == 'rateCompany'){
        $idCompany = $_POST['idCompany'];
        $rate = $_POST['rate'];
        $note = $RatingController->getNote($idCompany, $idUser);
        if ($note['ID_COMPANY'] == $idCompany && $note['ID_USER'] == $idUser){
            $RatingController->updateNote($idCompany, $idUser, $rate);
        }else{
            $RatingController->addNote($idCompany, $idUser, $rate);
        }
        header("Location: ?uri=/details-company&id=".$idCompany);
    }
    if ($_GET['action'] === 'addToWishlist') {
        $WishlistController->addOfferToWishlist($idUser);
        header("Location: ?uri=/profile");
        exit;
    }
    if ($_GET['action'] === 'removeFromWishlist') {
        $WishlistController->removeOfferFromWishlist();
        header("Location: ?uri=/profile");
        exit;
    }
    if ($_GET['action']=='filterstudents'){
        $keywords = $_POST['keywords'] ?? null;

        $uri='/dashboard';
    
    }
    if ($_GET['action']=='adduser'){
        $UserController->addUser();
        $uri='/dashboard';
    }

    if ($_GET['action']=='edituser'){
        $UserController->editUser();
        $uri='/dashboard';
    }

    if ($_GET['action']=='deleteuser'){
        $UserController->deleteUser();
        $uri='/dashboard';
    }

      if ($_GET['action'] === 'addoffer') {
        $OfferController->addOffer();
        exit;
    }


}
/**/

/* URL management */
elseif (isset($_GET['uri'])) {
    $uri = $_GET['uri'];
}

switch ($uri) {
    case '/':
        if (isset($idUser)){
            $OfferController->printOffers('home.twig', 3, []);
        }else{
            header("Location: ?uri=/connection");
        }
        break;


    case '/companies':
        $CompanyController->index();
        break;
    case '/create-company':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $CompanyController->addCompany();
        } else {
            echo $twig->render('create-company.twig');
        }
        break;
    
    case '/edit-company':
        if (isset($_GET['id'])) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $CompanyController->updateCompany();
            } else {
                $company = $CompanyController->getCompany($_GET['id']);
                echo $twig->render('edit-company.twig', ['company' => $company]);
            }
        } else {
            echo "ID de l'entreprise manquant.";
        }
        break;
    
    case '/delete-company':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID'])) {
            $CompanyController->deleteCompany();
        } else {
            echo "ID de l'entreprise manquant.";
        }
        break;
     case '/create-offer':
        if ($id_role === 2 || $id_role === 3) {
            $OfferController->showOfferForm();
            
        } else {
            echo "Accès refusé.";
        }
        break;
        
    case '/edit-offer':
        if ($id_role === 2 || $id_role === 3) {
            if (isset($_GET['id'])) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $OfferController->updateOffer();
                } else {
                    $offer = $OfferController->getOffer($_GET['id']);
                    echo $twig->render('edit-offer.twig', ['offer' => $offer, 'id_role' => $id_role]);
                }
            } else {
                echo "ID de l'offre manquant.";
            }
        } else {
            echo "Accès refusé.";
        }
        break;
        
   case '/delete-offer':
        if (($id_role === 2 || $id_role === 3) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID'])) {
            $OfferController->deleteOffer();
        } else {
            echo "Accès refusé ou ID de l'offre manquant.";
        }
        break;
        

    case '/browse':
        if (isset($idUser)){
            $OfferController->printOffers('browse.twig', 5, [$keywords,$duration,$experience]);
        }else{
            header("Location: ?uri=/connection");
        }
        break;
    case '/details-offer':
        if (isset($idUser)){
            $offerId = $_GET['id'] ?? null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ApplicationController->addApplication();
            }
            $OfferController->printSpecificOffer($offerId);
        }else{
            header("Location: ?uri=/connection");
        }
        break;

     case '/RGPD':
        echo $twig->render('RGPD.twig', ['firstname' => $firstname, 'id_role' => $id_role]);
        break;
    case '/profile':
        if(isset($_GET['idUser'])){
            $wishlist = $WishlistController->getWishlist($_GET['idUser']);
            $UserController->showUserProfile($_GET['idUser'],$wishlist);
        }

        else if(isset($firstname)){
            $wishlist = $WishlistController->getWishlist($idUser);
            $UserController->showUserProfile($idUser,$wishlist);
            
        }
        else{
            header("Location: ?uri=/connection");
        }
        break;
    case '/connection':
        echo $twig->render('connection.twig', ['firstname' => $firstname, 'id_role' => $id_role]);
        break;
    case '/connectionwrong':
        echo $twig->render('connection.twig', ['firstname' => $firstname, 'id_role' => $id_role, 'connected' => "false"]);
        break;
    case '/details-company':
        if (isset($idUser)){
            $companyId = $_GET['id'];
            $CompanyController->printCompany($companyId, $firstname, $id_role);
        }else{
            header("Location: ?uri=/connection");
        }
        break;
    case '/dashboard':
        if($id_role==2){
            $UserController->PrintAllUsersFromPilote($idUser,$keywords);
        }
        else if($id_role==3){
            $UserController->PrintAllUsers($keywords);
        }
        break;
    default:
        echo 'Page not found';
        break;
}
/**/
