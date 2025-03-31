<?php
namespace App\Models;

require_once 'src/Models/Model.php';
require_once 'src/Models/Database.php';

use PDO;

class ApplicationModel extends Model
{
    public function __construct($connection = null)
    {
        if (is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllApplications(): array
    {
        return $this->connection->getAllRecords("Applications");
    }

    public function getApplication(int $id): ?array
    {
        return $this->connection->getRecord("Applications", $id);
    }

    public function addApplication(string $cv, string $coverLetter, int $userId, int $offerId): int
    {
        $record = [
            'CV' => $cv,
            'COVER_LETTER' => $coverLetter,
            'STUDENT_ID' => $userId,
            'OFFER_ID' => $offerId,
        ];
        return $this->connection->insertRecord("Applications", $record);
    }

    public function updateApplication(int $id, string $cv, string $coverLetter): int
    {
        $record = [
            'CV' => $cv,
            'COVER_LETTER' => $coverLetter,
        ];

        $condition = "ID = :id";
        $params = [':id' => $id];

        return $this->connection->updateRecord("Applications", $record, $condition, $params);
    }

    public function deleteApplication(int $id): int
    {
        return $this->connection->deleteRecord("Applications", $id);
    }

    public function __destruct() {}
}
