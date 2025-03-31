<?php
namespace App\Models;

require_once __DIR__.'/Model.php';
require_once __DIR__ . '/Database.php';

use App\Models\Database;

class PermissionModel extends Model {

    public function __construct($connection = null) {
        if (is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllPermissions(): array {
        return $this->connection->getAllRecords("permissions");
    }

    public function getPermission(int $id): ?array {
        return $this->connection->getRecord("permissions", $id);
    }

    public function createPermission(string $description): int {
        $record = [
            'DESCRIPTION' => $description
        ];
        return $this->connection->insertRecord("permissions", $record);
    }

    public function updatePermission(int $id, string $description): int {
        $record = [
            'DESCRIPTION' => $description
        ];
        $condition = "id = :id";
        $paramsCondition = [':id' => $id];
        return $this->connection->updateRecord("permissions", $record, $condition, $paramsCondition);
    }

    public function deletePermission(int $id): int {
        return $this->connection->deleteRecord("permissions", $id);
    }
}
