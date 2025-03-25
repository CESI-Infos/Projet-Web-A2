<?php
namespace App\Models;

require_once 'FileDatabase.php';
require_once 'Database.php';
require_once 'Model.php';

use App\Models\FileDatabase;
use App\Models\Model;

class CompanyModel extends Model{

    public function __construct($db=NULL){
        if (is_null($db)){
            $this->connection = new FileDatabase('../../.csv/TestCOMPANIES', ['Name', 'Description', 'Mail', 'Phone']);
        }else{
            $this->connection = new FileDatabase($db, ['Name', 'Description', 'Mail', 'Phone']);
        }
    }

    public function getAllCompanies() {
        return $this->connection->getAllRecords();
    }

    public function getCompany($id) {
        return $this->connection->getRecord($id);
    }

    public function changeCompanyName($id, $name){
        $comp = $this->getCompany($id);
        $comp['NAME'] = $name;
        $this->connection->updateRecord($id, $comp);
    }

    public function changeCompanyDescription($id, $descript){
        $comp = $this->getCompany($id);
        $comp['DESCRIPTION'] = $descript;
        $this->connection->updateRecord($id, $comp);
    }

    public function changeCompanyMail($id, $mail){
        $comp = $this->getCompany($id);
        $comp['MAIL'] = $mail;
        $this->connection->updateRecord($id, $comp);
    }

    public function changeCompanyPhone($id, $phone){
        $comp = $this->getCompany($id);
        $comp['PHONE'] = $phone;
        $this->connection->updateRecord($id, $comp);
    }

    public function createCompany($name){
        $comp = [
            'NAME' => $name,
            'DESCRIPTION' => '',
            'MAIL' => '',
            'PHONE' => '',
        ];
        return $this->connection->insertRecord($comp);
    }

    public function deleteCompany($id){
        return $this->connection->deleteRecord($id);
    }
}