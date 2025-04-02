<?php
namespace App\Controllers;

require_once "src/Models/UserModel.php";
require_once "src/Controllers/Controller.php";
require_once "src/Models/ApplicationModel.php";

use App\Models\UserModel;
use App\Controllers\Controller;
use App\Models\ApplicationModel;

class UserController extends Controller{

    public function __construct($templateEngine) {
        $this->model = new UserModel();
        $this->templateEngine = $templateEngine;
    }

    public function addUser(){
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        $id_role = $_POST['id_role'];
        $id_pilote = $_POST['id_pilote'] ?? null;
        
        $this->model->addUser($firstname, $lastname, $mail, $password, $id_role, $id_pilote);
    }

    public function editUser() {
        $id = $_POST['id'];
        $firstname = $_POST['firstname'] ?? null;
        $lastname = $_POST['lastname'] ?? null;
        $mail = $_POST['mail'] ?? null;
        $password = $_POST['password'] ?? null;
        $id_role = $_POST['id_role'] ?? null;
        $id_pilote = $_POST['id_pilote'] ?? null;

        $this->model->editUser($id, $firstname, $lastname, $mail, $password, $id_role, $id_pilote);
    }

    public function deleteUser() {
        $id = $_POST['id'];

        $this->model->deleteUser($id);
    }
    // Allows the user to authenticate
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
            $_SESSION['idUser']=$user["ID"];
            header("Location: ?uri=/");
        }else{
            header("Location: ?uri=/connectionwrong");
        }
        exit;
    }
    // Allows the user to log out
    public function disconnect(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        header("Location: ?uri=/profile");
    }
    // Retrieves all users for the pilot
    public function PrintAllUsersFromPilote($id_pilote,$keywords){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id_role = $_SESSION['id_role'] ?? null;
        $firstname = $_SESSION['firstname'] ?? null;
        $students = $this->model->getAllUsersFromPilote($id_pilote,$keywords);
        echo $this->templateEngine->render('dashboard.twig', ['students' => $students, 'firstname' => $firstname, 'id_role' => $id_role,'idUser' => $id_pilote]);
    }

    public function PrintAllUsers($keywords){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $id_role = $_SESSION['id_role'] ?? null;
        $firstname = $_SESSION['firstname'] ?? null;
        $students = $this->model->getAllUsers($keywords);
        $pilotes = $this->model->getAllPilotes();
        echo $this->templateEngine->render('dashboard.twig', ['students' => $students, 'firstname' => $firstname, 'id_role' => $id_role,'pilotes' =>$pilotes]);
    }
    // Returns the profile of a user
    public function showUserProfile($idUser = null, $wishlist = null)
{
    if ($idUser == null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $idUser = $_SESSION['idUser'];
    }

    $user = $this->model->getUserById($idUser);

    $applicationModel = new ApplicationModel();
    $offers = $applicationModel->getOffersAppliedByUser($idUser);

    $id_role = $_SESSION['id_role'] ?? null;
    $firstname = $_SESSION['firstname'] ?? null;

    echo $this->templateEngine->render('profile.twig', [
        'entity'    => $user,
        'firstname' => $firstname,
        'id_role'   => $id_role,
        'isUser'    => true,
        'wishlist'  => $wishlist,
        'offers'    => $offers,           
        'count'     => count($offers)     
    ]);
}
}