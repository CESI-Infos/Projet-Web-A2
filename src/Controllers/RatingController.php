<?php

class RatingController
{
    
    private $noteModel;

    public function __construct(RatingModel $model)
    {
        $this->noteModel = $model;
    }

    public function getNote(int $idCompany, int $idUser): int
    {
        return $this->noteModel->getNote($idCompany, $idUser);
    }

    public function addNote(int $idCompany, int $idUser, int $note): void
    {
        $this->noteModel->addNote($idCompany, $idUser, $note);
    }

    public function deleteNote(int $idCompany, int $idUser): void
    {
        $this->noteModel->deleteNote($idCompany, $idUser);
    }
}
