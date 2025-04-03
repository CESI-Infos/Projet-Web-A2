<?php
namespace App\Controllers;

require_once "src/Models/OfferModel.php";
require_once "src/Controllers/Controller.php";

use App\Controllers\Controller;
use App\Models\OfferModel;
use App\Models\CompanyModel;

class OfferController extends Controller {
    private $companyModel;
    
    public function __construct($templateEngine) {
        $this->model = new OfferModel();
        $this->companyModel = new CompanyModel();
        $this->templateEngine = $templateEngine;
    }

    public function showOfferForm() {
        $companies = $this->companyModel->getAllCompanies();
        echo $this->templateEngine->render('create-offer.twig', ['companies' => $companies]);
    }


    public function addOffer() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SESSION['id_role'] !== 2 && $_SESSION['id_role'] !== 3) {
            echo "Accès refusé.";
            exit();
        }

        // Vérification des champs obligatoires
        $requiredFields = ["TITLE", "RELEASE_DATE", "CITY", "GRADE", "BEGIN_DATE", "DURATION", "RENUMBER", "DESCRIPTION", "ID_COMPANY"];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                echo "Le champ $field est obligatoire.";
                exit();
            }
        }

        // Récupération des valeurs
        $title = $_POST["TITLE"];
        $releaseDate = $_POST["RELEASE_DATE"];
        $city = $_POST["CITY"];
        $grade = $_POST["GRADE"];
        $beginDate = $_POST["BEGIN_DATE"];
        $duration = $_POST["DURATION"];
        $renumber = $_POST["RENUMBER"];
        $description = $_POST["DESCRIPTION"];
        $idCompany = $_POST["ID_COMPANY"];

        // Ajout de l'offre
        $this->model->createOffer($title, $releaseDate, $city, $grade, $beginDate, $duration, $renumber, $description, $idCompany);
        
        header('Location: ?uri=/browse');
        exit();
    }

    public function updateOffer() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SESSION['id_role'] !== 2 && $_SESSION['id_role'] !== 3) {
            echo "Accès refusé.";
            exit();
        }
        if (!isset($_POST["ID"])) {
            echo "ID de l'offre manquant.";
            exit();
        }

        // Vérification des champs obligatoires
        $requiredFields = ["ID", "TITLE", "RELEASE_DATE", "CITY", "GRADE", "BEGIN_DATE", "DURATION", "RENUMBER", "DESCRIPTION", "ID_COMPANY"];
        foreach ($requiredFields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                echo "Le champ $field est obligatoire.";
                exit();
            }
        }

        // Récupération des valeurs
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

        // Modification de l'offre
        $this->model->updateOffer($id, $title, $releaseDate, $city, $grade, $beginDate, $duration, $renumber, $description, $idCompany);

        header('Location: ?uri=/browse');
        exit();
    }

    public function deleteOffer() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SESSION['id_role'] !== 2 && $_SESSION['id_role'] !== 3) {
    echo "Accès refusé.";
    exit();
}

        if (!isset($_POST["ID"])) {
            echo "ID de l'offre manquant.";
            exit();
        }

        $id = $_POST["ID"];
        $this->model->deleteOffer($id);

        header('Location: ?uri=/browse');
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
        
        echo $this->templateEngine->render($pages, ['offers' => $offers, 'page' => $page, 'totalPages' => $totalPages, 'firstname' => $firstname, 'id_role' => $id_role]);
    }

    public function printSpecificOffer($id) {
        $champs = "Offers.ID, Offers.TITLE, Offers.RELEASE_DATE, Offers.CITY, Offers.GRADE, Offers.BEGIN_DATE, Offers.DURATION, Offers.RENUMBER, Offers.DESCRIPTION AS OFFER_DESCRIPTION, Offers.ID_COMPANY, Companies.NAME, Companies.DESCRIPTION AS COMPANY_DESCRIPTION";
        $offer = $this->model->getOffer($id, $champs);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $id_role = $_SESSION['id_role'] ?? null;
        $firstname = $_SESSION['firstname'] ?? null;
     
        echo $this->templateEngine->render('details-offer.twig', ['offer' => $offer, 'firstname' => $firstname, 'id_role' => $id_role]);
    }
    // Récupérer une offre pour l'édition
    public function getOffer($id) {
        return $this->model->getOffer($id);
    }
}
