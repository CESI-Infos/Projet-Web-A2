<?php
namespace App\Controllers;

require_once "src/Models/OfferModel.php";
require_once "src/Models/CompanyModel.php";
require_once "src/Controllers/Controller.php";
require_once "src/Controllers/RatingController.php";

use App\Models\OfferModel;
use App\Models\CompanyModel;
use App\Controllers\Controller;
use App\Controllers\RatingController;

class CompanyController extends Controller {

    public function __construct($templateEngine) {
        $this->model = new CompanyModel();
        $this->templateEngine = $templateEngine;
    }

    public function index() {
        $companies = $this->model->getAllCompanies();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id_role = $_SESSION['id_role'] ?? null;
        $firstname = $_SESSION['firstname'] ?? null;
        echo $this->templateEngine->render('browsecompanies.twig', ['companies' => $companies,'firstname' => $firstname,'id_role' =>$id_role]);
    }


    public function getCompany($id) {
        return $this->model->getCompany($id);
    }
    

    public function addCompany() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['NAME'] ?? '';
            $descript = $_POST['DESCRIPTION'] ?? '';
            $mail = $_POST['MAIL'] ?? '';
            $phone = $_POST['PHONE'] ?? '';

            if ($name) {
                $id = $this->model->createCompany($name, $descript, $mail, $phone);
                header('Location: /?uri=/browsecompanies');
                exit;
            }
        }
        echo $this->templateEngine->render('create-company.twig');
    }
    public function updateCompany() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID'])) {
            $id = $_POST['ID'];
            $name = $_POST['NAME'] ?? '';
            $description = $_POST['DESCRIPTION'] ?? '';
            $mail = $_POST['MAIL'] ?? '';
            $phone = $_POST['PHONE'] ?? '';
    
            if (!empty($id) && !empty($name)) {
                $this->model->updateCompany($id, $name, $description, $mail, $phone);
                header('Location: /?uri=/browsecompanies');
                exit;
            }
        }
        echo "Erreur lors de la modification.";
    }
    
    
    
    

    public function deleteCompany() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID'])) {
            $this->model->deleteCompany($_POST['ID']);
            header('Location: /?uri=/browsecompanies');
            exit;
        }
        echo "ID de l'entreprise manquant.";
    }

    public function printCompany($id, $firstname, $id_role) {
        $company = $this->model->getCompany($id);
        if (!$company) {
            echo "Entreprise introuvable.";
            return;
        }

        $OfferModel = new OfferModel();
        $AllOffers = $OfferModel->getAllOffers();
        $offers = array_filter($AllOffers, function($offer) use ($id) {
            return $offer['ID_COMPANY'] == $id;
        });

        $notes = new RatingController();
        $note = "Note de l'entreprise: " . $notes->NoteMoyenne($id);

        echo $this->templateEngine->render('profile.twig', [
            'entity' => $company,
            'offers' => $offers,
            'count' => count($offers),
            'note' => $note,
            'firstname' => $firstname,
            'id_role' => $id_role
        ]);
    }
}
