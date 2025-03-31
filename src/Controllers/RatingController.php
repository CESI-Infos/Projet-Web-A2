<?php
namespace App\Controllers;

require_once "src/Models/RatingModel.php";
require_once "src/Controllers/Controller.php";

use App\Models\RatingModel;
use App\Controllers\Controller;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->model = new RatingModel();
    }

    public function getNote(int $idCompany, int $idUser): int
    {
        return $this->model->getNote($idCompany, $idUser);
    }

    public function NoteMoyenne($idCompany){
        $notes = $this->model->getAllNotes($idCompany);
        $sum = 0;
        $count = 0;
        foreach($notes as $note){
            if ($note['ID_COMPANY'] == $idCompany){
                $sum+=$note['GRADE'];
                $count+=1;
            }
        }
        return $sum/$count;
    }

    public function addNote(int $idCompany, int $idUser, int $note): void
    {
        $this->model->createNote($idCompany, $idUser, $note);
    }

    public function deleteNote(int $idCompany, int $idUser): void
    {
        $this->model->deleteNote($idCompany, $idUser);
    }
}
