<?php
namespace App\Models;

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Database.php';

use App\Models\Database;

class RoleModel extends Model
{
    public function __construct($connection = null)
    {
        if (is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function getAllRoles(): array
    {
        return $this->connection->getAllRecords("Roles");
    }

    public function getRole(int $id): ?array
    {
        return $this->connection->getRecord("Roles", $id);
    }

    public function createRole(string $name): int
    {
        $record = [
            'NAME' => $name
        ];
        return $this->connection->insertRecord("Roles", $record);
    }

    public function updateRole(int $id, string $name): int
    {
        $record = [
            'NAME' => $name
        ];
        $condition = "ID = :id";
        $paramsCondition = [':id' => $id];
        return $this->connection->updateRecord("Roles", $record, $condition, $paramsCondition);
    }

    public function deleteRole(int $id): int
    {
        return $this->connection->deleteRecord("Roles", $id, "ID");
    }
}
