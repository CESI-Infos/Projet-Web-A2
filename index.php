<?php
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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_role = $_SESSION['id_role'] ?? null;
$firstname = $_SESSION['firstname'] ?? null;
$idUser = $_SESSION['idUser'] ?? null;

$id_role = $_SESSION['id_role'] ?? null;
$firstname = $_SESSION['firstname'] ?? null;
$idUser = $_SESSION['idUser'] ?? null;

$uri = '/';

$keywords = null;
$duration = null;
$experience = null;


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

}

elseif (isset($_GET['uri'])) {
    $uri = $_GET['uri'];
}

switch ($uri) {
    case '/':
        $OfferController->printOffers('home.twig', 3, []);
        break;
    case '/browse':
        $OfferController->printOffers('browse.twig', 5, [$keywords,$duration,$experience]);
        break;
    case '/details-offer':
        $offerId = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ApplicationController->addApplication();
            header("Location: ?uri=/details-offer&id=$offerId&success=Votre candidature a bien été envoyée !");
            exit;
        }
        $OfferController->printSpecificOffer($offerId);
        break;
    case '/create-offer':
        echo $twig->render('create-offer.twig', ['firstname' => $firstname, 'id_role' => $id_role]);
        break;
    case '/support':
        echo $twig->render('support.twig', ['firstname' => $firstname, 'id_role' => $id_role]);
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
            echo $twig->render('connection.twig',['firstname' => $firstname,'id_role' =>$id_role]);
        }
        break;        
    case '/connectionwrong':
        echo $twig->render('connection.twig', ['firstname' => $firstname, 'id_role' => $id_role, 'connected' => "false"]);
        break;
    case '/sign-up':
        echo $twig->render('sign-up.twig', ['firstname' => $firstname, 'id_role' => $id_role]);
        break;
    case '/details-company':
        $companyId = $_GET['id'];
        $CompanyController->printCompany($companyId, $firstname, $id_role);
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