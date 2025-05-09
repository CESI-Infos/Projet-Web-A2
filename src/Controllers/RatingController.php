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

    public function getNote(int $idCompany, int $idUser)
    {
        $notes = $this->model->getAllNotes();
        foreach ($notes as $note){
            if ($note['ID_COMPANY'] == $idCompany && $note['ID_USER'] == $idUser){
                return $note;
            }
        }
        return -1;
    }
    // Average rating of a company
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
    // User's rating for a company
    public function addNote(int $idCompany, int $idUser, int $note): void
    {
        $this->model->createNote($idCompany, $idUser, $note);
    }
    // Update a user's rating for a company
    public function updateNote(int $idCompany, int $idUser, int $note){
        $this->model->updateNote($idCompany, $idUser, $note);
    }
    // Delete a user's rating for a company
    public function deleteNote(int $idCompany, int $idUser): void
    {
        $this->model->deleteNote($idCompany, $idUser);
    }
}
