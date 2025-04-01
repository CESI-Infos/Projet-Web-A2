<?php
namespace App\Models;

require_once 'Model.php';
require_once 'Database.php';

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
            'LETTER' => $coverLetter,
            'ID_USER' => $userId,
            'ID_OFFER' => $offerId,
        ];
        return $this->connection->insertRecord("Applications", $record);
    }

    public function updateApplication(int $id, string $cv, string $coverLetter): int
    {
        $record = [
            'CV' => $cv,
            'LETTER' => $coverLetter,
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

public function getOffersAppliedByUser(int $userId): array
{
    $sql = "
        SELECT Offers.*,
               Companies.NAME
          FROM Applications
          JOIN Offers ON Applications.ID_OFFER = Offers.ID
          JOIN Companies ON Offers.ID_COMPANY = Companies.ID
         WHERE Applications.ID_USER = :userId
    ";

    $stmt = $this->connection->prepare($sql);
    $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

}
