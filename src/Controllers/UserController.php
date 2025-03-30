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

    public function authenticate($mail,$password){
        $mail=$_POST["mail"];
        $password=$_POST["password"];
        $result=$this->model->authenticate($mail,$password);

        if (!empty($result)) {
            $user=$result[0];
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['connected']="true";
            $_SESSION['id_role'] = $user["ID_ROLE"];
            $_SESSION['mail'] = $user["MAIL"];
            $_SESSION['firstname']=$user["FIRSTNAME"];
            header("Location: ");
        }else{
            header("Location: ?uri=/connexionwrong");
        }
    }

    public function disconnect(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        header("Location: ?uri=/connexion");
    }

}