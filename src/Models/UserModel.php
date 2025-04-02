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

    public function addUser($firstname, $lastname, $mail, $password, $id_role, $id_pilote = null) {
        $params = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'mail' => $mail,
            'password' => $password,
            'id_role' => $id_role
        ];

        if (!is_null($id_pilote)) {
            $params['id_pilote'] = $id_pilote;
        }

        return $this->connection->insertRecord('Users', $params);
    }

    public function editUser($id, $firstname = null, $lastname = null, $mail = null, $password = null, $id_role = null, $id_pilote = null) {
        $params = [':id' => $id];
        $data = [];

        if (!is_null($firstname) && $firstname !== '') {
            $data['firstname'] = $firstname;
        }
        if (!is_null($lastname) && $lastname !== '') {
            $data['lastname'] = $lastname;
        }
        if (!is_null($mail) && $mail !== '') {
            $data['mail'] = $mail;
        }
        if (!is_null($password) && $password !== '') {
            $data['password'] = $password;
        }
        if (!is_null($id_role) && $id_role !== '') {
            $data['id_role'] = $id_role;
        }
        if (!is_null($id_pilote) && $id_pilote !== '') {
            $data['id_pilote'] = $id_pilote;
        }

        $condition = 'id = :id';

        return $this->connection->updateRecord('Users', $data, $condition, $params);
    }

    public function deleteUser($id) {
        $params = [
            ':id' => $id
        ];
        $condition = 'id = :id';

        return $this->connection->deleteRecordCondition('Users', $condition, $params);
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

    public function getAllPilotes() {
        $condition = 'id_role = :id_role';
        $params = [
            ':id_role' => 2
        ];

        return $this->connection->getRecordsWhen('Users', $condition, '', $params);
    }
}