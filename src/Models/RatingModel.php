<?php

class RatingModel
{
    
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getNote(int $idCompany, int $idUser): int
    {
        try {
            $sql = "SELECT note FROM ratings 
                    WHERE idCompany = :idCompany 
                      AND idUser = :idUser";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':idCompany', $idCompany, PDO::PARAM_INT);
            $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return (int)$row['note'];
            }
            return -1;
        } catch (PDOException $e) {
            return -1;
        }
    }

    public function addNote(int $idCompany, int $idUser, int $note): bool
    {
        try {
            $existing = $this->getNote($idCompany, $idUser);
            
            if ($existing === -1) {
                $sql = "INSERT INTO ratings (idCompany, idUser, note) 
                        VALUES (:idCompany, :idUser, :note)";
            } else {
                $sql = "UPDATE ratings 
                        SET note = :note
                        WHERE idCompany = :idCompany
                          AND idUser = :idUser";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':idCompany', $idCompany, PDO::PARAM_INT);
            $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindValue(':note', $note, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteNote(int $idCompany, int $idUser): bool
    {
        try {
            $sql = "DELETE FROM ratings
                    WHERE idCompany = :idCompany
                      AND idUser = :idUser";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':idCompany', $idCompany, PDO::PARAM_INT);
            $stmt->bindValue(':idUser', $idUser, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
