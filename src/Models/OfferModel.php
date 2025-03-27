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
        return $this->connection->getAllRecords("Offers");
    }

    public function getOffer($id) {
        return $this->connection->getRecord("Offers",$id);
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
