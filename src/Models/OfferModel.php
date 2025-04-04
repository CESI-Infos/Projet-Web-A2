<?php
namespace App\Models;

require_once 'src/Models/Model.php';
require_once 'src/Models/Database.php';

use App\Models\Model;
use App\Models\Database;

class OfferModel extends Model {
    
    public function __construct($connection = null) {
        if (is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllOffers(string $champs = "*") {
    return $this->connection->getAllRecords("Offers", "JOIN Companies ON Offers.ID_COMPANY = Companies.ID", $champs);
    }



    public function getOffersWhen($filters, string $champs = "*") {
        $conditions = [];
        $params = [];

        if (!empty($filters['0'])) {
            $conditions[] = "TITLE LIKE :keywords OR Offers.DESCRIPTION LIKE :keywords";
            $params[':keywords'] = '%' . $filters['0'] . '%';
        }

        if (!empty($filters['1'])) {
            $conditions[] = "DURATION <= :duration";
            $params[':duration'] = $filters['1'];
        }

        if (!empty($filters['2'])) {
            $conditions[] = "GRADE = :experience";
            $params[':experience'] = $filters['2'];
        }

        $conditionString = implode(' AND ', $conditions);
        if (empty($conditionString)) {
            return $this->getAllOffers($champs);
        }
        return $this->connection->getRecordsWhen(
            "Offers",
            $conditionString,
            "JOIN Companies ON Offers.id_company = Companies.id",
            $params,
            $champs
        );
    }
    public function getOffer($id, $champs = '*') {
        return $this->connection->getRecord("Offers", $id, "JOIN Companies ON Offers.ID_COMPANY = Companies.ID", $champs);
    }  

    public function getCompanyIdByName($name) {
        $sql = "SELECT ID FROM Companies WHERE NAME = :name";
        $stmt = $this->connection->prepare($sql); 
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch();
    
        return $result ? $result['ID'] : null;
    } 

    public function getOffersByCompany($idCompany) {
        return $this->connection->getAllRecords("Offers", "WHERE ID_COMPANY = :idCompany", [':idCompany' => $idCompany]);
    }

    public function createOffer($title, $release_date, $city, $grade, $begin_date, $duration, $renumber, $description, $id_company) {
        if (empty($title) || empty($release_date) || empty($city) || empty($grade) || empty($begin_date) || empty($duration) || empty($renumber) || empty($description) || empty($id_company)) {
            throw new \Exception("Tous les champs sont obligatoires.");
        }

        $record = [
            'TITLE' => $title,
            'RELEASE_DATE' => $release_date,
            'CITY' => $city,
            'GRADE' => $grade,
            'BEGIN_DATE' => $begin_date,
            'DURATION' => $duration,
            'RENUMBER' => $renumber,
            'DESCRIPTION' => $description,
            'ID_COMPANY' => $id_company
        ];

        return $this->connection->insertRecord("Offers", $record);
    }

    public function updateOffer($id, $title, $release_date, $city, $grade, $begin_date, $duration, $renumber, $description, $id_company) {
        if (empty($id) || empty($title) || empty($release_date) || empty($city) || empty($grade) || empty($begin_date) || empty($duration) || empty($renumber) || empty($description) || empty($id_company)) {
            throw new \Exception("Tous les champs sont obligatoires.");
        }

        $record = [
            'TITLE' => $title,
            'RELEASE_DATE' => $release_date,
            'CITY' => $city,
            'GRADE' => $grade,
            'BEGIN_DATE' => $begin_date,
            'DURATION' => $duration,
            'RENUMBER' => $renumber,
            'DESCRIPTION' => $description,
            'ID_COMPANY' => $id_company
        ];

        $condition = "ID = :id";
        $paramsCondition = [':id' => $id];
        return $this->connection->updateRecord("Offers", $record, $condition, $paramsCondition);
    }

    public function deleteOffer($id) {
        $offer = $this->getOffer($id);
        if (!$offer) {
            throw new \Exception("L'offre n'existe pas.");
        }
        
        return $this->connection->deleteRecord("Offers", $id);
    }
}
