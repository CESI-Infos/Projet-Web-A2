<?php
namespace App\Controllers;

require_once "src/Models/OfferModel.php";
require_once "src/Controllers/Controller.php";

use App\Controllers\Controller;
use App\Models\OfferModel;

class OfferController extends Controller {
    
    public function __construct($templateEngine) {
        $this->model = new OfferModel();
        $this->templateEngine = $templateEngine;
    }

    public function addOffer() {
        if (!isset($_POST["TITLE"]) || !isset($_POST["RELEASE_DATE"]) || !isset($_POST["CITY"]) ||
            !isset($_POST["GRADE"]) || !isset($_POST["BEGIN_DATE"]) || !isset($_POST["DURATION"]) || 
            !isset($_POST["RENUMBER"]) || !isset($_POST["DESCRIPTION"]) || !isset($_POST["ID_COMPANY"])) {
            header('Location: /');
            exit();
        }

        $title = $_POST["TITLE"];
        $releaseDate = $_POST["RELEASE_DATE"];
        $city = $_POST["CITY"];
        $grade = $_POST["GRADE"];
        $beginDate = $_POST["BEGIN_DATE"];
        $duration = $_POST["DURATION"];
        $renumber = $_POST["RENUMBER"];
        $description = $_POST["DESCRIPTION"];
        $idCompany = $_POST["ID_COMPANY"];

        $this->model->createOffer($title, $releaseDate, $city, $grade, $beginDate, $duration, $renumber, $description, $idCompany);
        header('Location: /');
        exit();
    }

    public function updateOffer() {
        if (!isset($_POST["ID"]) || !isset($_POST["TITLE"]) || !isset($_POST["RELEASE_DATE"]) || 
            !isset($_POST["CITY"]) || !isset($_POST["GRADE"]) || !isset($_POST["BEGIN_DATE"]) || 
            !isset($_POST["DURATION"]) || !isset($_POST["RENUMBER"]) || !isset($_POST["DESCRIPTION"]) || 
            !isset($_POST["ID_COMPANY"])) {
            header('Location: /');
            exit();
        }

        $id = $_POST["ID"];
        $title = $_POST["TITLE"];
        $releaseDate = $_POST["RELEASE_DATE"];
        $city = $_POST["CITY"];
        $grade = $_POST["GRADE"];
        $beginDate = $_POST["BEGIN_DATE"];
        $duration = $_POST["DURATION"];
        $renumber = $_POST["RENUMBER"];
        $description = $_POST["DESCRIPTION"];
        $idCompany = $_POST["ID_COMPANY"];

        $this->model->updateOffer($id, $title, $releaseDate, $city, $grade, $beginDate, $duration, $renumber, $description, $idCompany);
        header('Location: /');
        exit();
    }

    public function deleteOffer($id) {
        $this->model->deleteOffer($id);
        header('Location: /');
        exit();
    }

    public function printOffers($pages, $num) {
        $allOffers = $this->model->getAllOffers();
        $allnum = count($allOffers);
        $totalPages = ceil($allnum / $num);
        
        $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        
        $offers = array_slice($allOffers, $num * ($page - 1), $num);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id_role = $_SESSION['id_role'] ?? null;
        $firstname = $_SESSION['firstname'] ?? null;
        echo $this->templateEngine->render($pages, ['offers' => $offers, 'page' => $page, 'totalPages' => $totalPages,'firstname' => $firstname,'id_role' =>$id_role]);
    }

    public function printSpecificOffer($id) {
        $champs = "Offers.ID, Offers.TITLE, Offers.RELEASE_DATE, Offers.CITY, Offers.GRADE, Offers.BEGIN_DATE, Offers.DURATION, Offers.RENUMBER, Offers.DESCRIPTION AS OFFER_DESCRIPTION, Companies.NAME, Companies.DESCRIPTION AS COMPANY_DESCRIPTION";
        $offer = $this->model->getOffer($id, $champs);
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id_role = $_SESSION['id_role'] ?? null;
        $firstname = $_SESSION['firstname'] ?? null;
        echo $this->templateEngine->render('details-offre.twig', ['offer' => $offer,'firstname' => $firstname,'id_role' =>$id_role]);
    }
}
