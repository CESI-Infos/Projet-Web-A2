<?php
namespace App\Models;
require_once 'src/Models/Model.php';


class UserModel extends Model{
    public function __construct($connection = null) {
        if(is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllUsers() {
        return $this->connection->getAllRecords("Users");
    }

    public function getUser($id) {
        return $this->connection->getRecord("Users",$id);
    }

    public function addUser($mail,$password) {
        $record = [
            'lastname' => '',
            'firstname' => '',
            'mail' => $mail,
            'password' => $password,
            'phone' => '',
            'cv' => '',
            'letter' => '',
            'status' => ''
        ];

        return $this->connection->insertRecord("Users",$record);
    }

    public function updateUser($id, $lastname, $firstname, $password, $mail, $status) {
        $data = [
            'lastname' => $lastname,
            'firstname' => $firstname,
            'password' => $password,
            'mail' => $mail,
            'status' => $status
        ];
        $condition = 'id = :id';
        return $this->connection->updateRecord('Users', $data, $condition, [':id' => $id]);
    }

    public function deleteOffer($id) {
        return $this->connection->deleteRecord("Users",$id);
    }
}