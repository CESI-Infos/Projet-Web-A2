<?php
namespace App\Models;

require_once "src/Models/Model.php";

use App\Models\Model;

class RatingModel extends Model
{
    public function __construct($connection = null) {
        if (is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllNotes(){
        return $this->connection->getAllRecords("Note");
    }
    
    public function createNote(int $idCompany, int $idUser, int $note): bool
    {
        $record = [
            'ID_COMPANY' => $idCompany,
            'ID_USER' => $idUser,
            'GRADE' => $note,
        ];

        return $this->connection->insertRecord("Note", $record);
    }

    public function updateNote(int $idCompany, int $idUser, int $note): bool
    {
        $record = [
            'ID_COMPANY' => $idCompany,
            'ID_USER' => $idUser,
            'GRADE' => $note,
        ];

        return $this->connection->updateRecord("Note", $record, "ID_COMPANY = '$idCompany' AND ID_USER = '$idUser'");
    }

    public function deleteNote(int $idCompany, int $idUser): bool
    {
        return $this->connection->deleteRecord("Companies", $id);
    }
}
