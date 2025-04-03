<?php
namespace App\Models;

require_once 'src/Models/Model.php';

class CompanyModel extends Model {
    public function __construct($connection = null) {
        if (is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllCompanies() {
        return $this->connection->getAllRecords("Companies");
    }

    public function getCompany($id) {
        return $this->connection->getRecord("Companies", $id);
    }

    public function createCompany($name, $description, $mail, $phone) {
        $record = [
            'NAME' => $name,
            'DESCRIPTION' => $description,
            'MAIL' => $mail,
            'PHONE' => $phone,
        ];

        return $this->connection->insertRecord("Companies", $record);
    }

    public function updateCompany($id, $name, $description, $mail, $phone) {
        $record = [
            'NAME' => $name,
            'DESCRIPTION' => $description,
            'MAIL' => $mail,
            'PHONE' => $phone,
        ];
    
        $condition = "id = :id";
        $paramsCondition = [':id' => $id];
    
        $result = $this->connection->updateRecord("Companies", $record, $condition, $paramsCondition);
    }
    

    public function deleteCompany($id) {
        return $this->connection->deleteRecord("Companies", $id);
    }
}
