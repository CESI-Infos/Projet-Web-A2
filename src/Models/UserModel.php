<?php
namespace App\Models;

require_once 'src/Models/Model.php';
require_once "src/Models/Database.php";

use App\Models\Model;
use App\Models\Database;

class UserModel extends Model{
    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function authenticate($mail,$password){
        $params = [
            ':mail' => $mail,
            ':password' => $password
        ];
        return $this->connection->getRecordsWhen('Users', 'mail = :mail AND password = :password', '', $params);
    }

    public function getAllUsers(){
        return $this->connection->getAllRecords('Users');
    }
}