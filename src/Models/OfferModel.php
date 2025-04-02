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

    public function getAllOffers() {
    return $this->connection->getAllRecords("Offers", "JOIN Companies ON Offers.id_company = Companies.id");
    }
    // Allows retrieving offers based on multiple criteria
    public function getOffersWhen($filters) {
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
            return $this->getAllOffers();
        }
        return $this->connection->getRecordsWhen(
            "Offers",
            $conditionString,
            "JOIN Companies ON Offers.id_company = Companies.id",
            $params
        );
    }
  
    public function getOffer($id, $champs='*') {
        return $this->connection->getRecord("Offers", $id, "JOIN Companies ON Offers.id_company = Companies.id", $champs);
    }

    public function createOffer($title, $release_date, $city, $grade, $begin_date, $duration, $renumber, $description, $id_company) {
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

        return $this->connection->insertRecord("Offers",$record);
    }

    public function updateOffer($id, $title, $release_date, $city, $grade, $begin_date, $duration, $renumber, $description, $id_company) {
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

        $condition = "id = :id";
        $paramsCondition = [':id' => $id];
        return $this->connection->updateRecord("Offers", $record, $condition, $paramsCondition);
    }

    public function deleteOffer($id) {
        return $this->connection->deleteRecord("Offers",$id);
    }
}