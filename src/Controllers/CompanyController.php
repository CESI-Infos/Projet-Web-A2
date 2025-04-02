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
        echo $this->templateEngine->render('companies.twig', ['companies' => $companies]);
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
                header('Location: /?uri=/companies');
                exit;
            }
        }
        echo $this->templateEngine->render('create-company.twig');
    }
    public function updateCompany() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST); // Vérifie les données POST envoyées
            exit;
        }
    
        if (isset($_GET['id'])) {
            var_dump($_GET); // Vérifie ce que contient $_GET
            $company = $this->model->getCompany($_GET['id']);
            var_dump($company); // Vérifie ce que retourne getCompany()
            exit;
        } else {
            echo "ID de l'entreprise manquant.";
        }
    }
    
    
    

    public function deleteCompany() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ID'])) {
            $this->model->deleteCompany($_POST['ID']);
            header('Location: /?uri=/companies');
            exit;
        }
        echo "ID de l'entreprise manquant.";
    }

    public function printCompany($id) {
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
            'zz' => $company,
            'offers' => $offers,
            'count' => count($offers),
            'note' => $note
        ]);
    }
}
