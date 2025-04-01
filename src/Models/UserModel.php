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

    public function getAllUsers($keywords){
        
        
        if (!empty($keywords)) {
            $condition = 'firstname LIKE :keywords OR lastname LIKE :keywords OR mail LIKE :keywords';
            $params[':keywords'] = '%' . $keywords . '%';
            return $this->connection->getRecordsWhen('Users', $condition, '', $params);
        }
        return $this->connection->getAllRecords('Users');
    }
    public function getAllUsersFromPilote($id_pilote, $keywords) {
        $params = [
            ':id_pilote' => $id_pilote
        ];
        $condition = 'id_pilote = :id_pilote';

        if (!empty($keywords)) {
            $condition .= ' AND (firstname LIKE :keywords OR lastname LIKE :keywords OR mail LIKE :keywords)';
            $params[':keywords'] = '%' . $keywords . '%';
        }

        return $this->connection->getRecordsWhen('Users', $condition, '', $params);
    }
    public function getUserById($userId) {
        $params = [
            ':id' => $userId
        ];
        $condition = 'id = :id';

        return $this->connection->getRecordsWhen('Users', $condition, '', $params)[0];
    }
}