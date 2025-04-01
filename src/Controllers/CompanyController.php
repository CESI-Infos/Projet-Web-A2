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

class CompanyController extends Controller{

    public function __construct($templateEngine) {
        $this->model = new CompanyModel();
        $this->templateEngine = $templateEngine;
    }

    public function updateCompany(){
        $id = $_POST['ID'];
        $name = $_POST['NAME'];
        $descript = $_POST['DESCRIPTION'];
        $mail = $_POST['MAIL'];
        $phone = $_POST['PHONE'];

        $this->model->changeCompanyName($id, $name);
        $this->model->changeCompanyDescription($id, $descript);
        $this->model->changeCompanyMail($id, $mail);
        $this->model->changeCompanyPhone($id, $phone);
        header('Location: /');
        exit;
    }

    public function addCompany(){
        $name = $_POST['NAME'];
        $descript = $_POST['DESCRIPTION'];
        $mail = $_POST['MAIL'];
        $phone = $_POST['PHONE'];

        $this->model->createCompany($name);
        $id = count($this->model(getAllCompanies()));
        $this->model->changeCompanyDescription($id, $descript);
        $this->model->changeCompanyMail($id, $mail);
        $this->model->changeCompanyPhone($id, $phone);
        header('Location: /');
        exit;
    }

    public function deleteCompany(){
        $id = $_POST['ID'];
        $this->model->deleteCompany($id);
        header('Location: /');
        exit;
    }

    public function printCompany($id, $firstname, $id_role){
        $company = $this->model->getCompany($id);
        $OfferModel = new OfferModel();
        $AllOffers = $OfferModel->getAllOffers();
        $offers = [];
        $count = 0;
        foreach ($AllOffers as $offer){
            if ($offer['ID_COMPANY'] == $id){
                $offers[] = $offer;
                $count=+1;
            }
        }
        $notes = new RatingController();
        $note = "Note de l'entreprise: ".$notes->NoteMoyenne($id);
        echo $this->templateEngine->render('profile.twig', ['entity' => $company, 'offers' => $offers, 'count' => $count, 'note' => $note]);
    }
}