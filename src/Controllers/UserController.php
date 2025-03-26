<?php
namespace App\Controllers;

require_once "src/Models/UserModel.php";
require_once "src/Controllers/Controller.php";

use App\Models\UserModel;
use App\Controllers\Controller;

class UserController extends Controller{

    public function __construct($templateEngine) {
        $this->model = new UserModel();
        $this->templateEngine = $templateEngine;
    }

    public function addUser(){
        if(!isset($_POST["mail"]) || !isset($_POST["password"])){
            header('Location: /');
            exit();
        }
        $mail=$_POST["mail"];
        $password=$_POST["password"];
        $this->model->addUser($mail,$password);
        header('Location: /');
        exit();
    }

    public function updateUser($id,$lastname, $firstname, $password, $mail, $status){
        $id=$_POST["id"];
        $lastname=$_POST["lastname"];
        $firstname=$_POST["firstname"];
        $password=$_POST["password"];
        $mail=$_POST["mail"];
        $status=$_POST["status"];
        $this->model->updateUser($id,$lastname, $firstname, $password, $mail, $status);
        header('Location: /');
        exit();
    }

    public function deleteUser($id){
        //TODO
    }
}