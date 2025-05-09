<?php
namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private PDO $pdo;
    
    public function __construct()
    {
        $host = 'localhost';
        $dbname = 'TheGoodPlan';
        $user = 'thegoodplan';
        $password = 'z*hOCxRvGR@CU2MA';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }
    // Allows preparing an SQL query
    public function prepare(string $sql)
    {
        return $this->pdo->prepare($sql);
    }
    // Allows retrieving all records from a table
    public function getAllRecords(string $table, $jointure = '', string $champs = "*"): array
    {
        $sql = "SELECT $champs FROM $table $jointure";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Retrieves all records from a table with a specific condition
    public function getRecordsWhen(string $table, string $condition, $jointure = '', array $params = [], string $champs = "*"): array
    {
        $sql = "SELECT $champs FROM $table $jointure WHERE $table.$condition";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Retrieves a specific record from a table
    public function getRecord(string $table, $id, $jointure = '', string $champs = "*", string $colonneId = "id"): ?array
    {
        $sql = "SELECT $champs FROM $table $jointure WHERE $table.$colonneId = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
    // Allows inserting a record into a table
    public function insertRecord(string $table, array $data): int
    {
        $champs = implode(', ', array_keys($data));
        $parametres = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO $table ($champs) VALUES ($parametres)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $colonne => $valeur) {
            $stmt->bindValue(':' . $colonne, $valeur);
        }

        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }
    // Allows modifying a record in a table
    public function updateRecord(string $table, array $data, string $condition, array $paramsCondition = []): int
    {
        $champs = [];
        foreach ($data as $colonne => $valeur) {
            $champs[] = "$colonne = :update_$colonne";
        }
        $champsSQL = implode(', ', $champs);

        $sql = "UPDATE $table SET $champsSQL WHERE $condition";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $colonne => $valeur) {
            $stmt->bindValue(":update_$colonne", $valeur);
        }

        foreach ($paramsCondition as $placeholder => $valeur) {
            $stmt->bindValue($placeholder, $valeur);
        }

        $stmt->execute();
        return $stmt->rowCount();
    }
    // Allows deleting a record from a table
    public function deleteRecord(string $table, $id, string $colonneId = "id"): int
    {
        $sql = "DELETE FROM $table WHERE $colonneId = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }
    // Allows deleting a record from a table with a condition
    public function deleteRecordCondition(string $table, string $condition, array $params = []): int
    {
        $sql = "DELETE FROM $table WHERE $condition";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
}
