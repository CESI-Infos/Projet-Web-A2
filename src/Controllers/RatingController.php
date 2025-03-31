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
        $notes = $this->model->getCompanyNotes($idCompany);
        $sum = 0;
        foreach($notes as $note){
            $sum+=$note['GRADE'];
        }
        return $sum/count($notes);
    }

    public function addNote(int $idCompany, int $idUser, int $note): void
    {
        $this->model->addNote($idCompany, $idUser, $note);
    }

    public function deleteNote(int $idCompany, int $idUser): void
    {
        $this->model->deleteNote($idCompany, $idUser);
    }
}
