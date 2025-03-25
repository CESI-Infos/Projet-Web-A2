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

    public function printOffers($page, $num) {
        $allnum = count($this->model->getAllOffers());
        $offers = [];
        for ($i=0; $i<$num; $i++){
            $offers[] = $this->model->getOffer(rand(1, $allnum));
        }
        echo $this->templateEngine->render($page, ['offers' => $offers]);
    }
}
