<?php

class PermissionController
{
    private $permissionModel;

    public function __construct(PDO $db)
    {
        $this->permissionModel = new PermissionModel($db);
    }

    public function listPermissions(): array
    {
        return $this->permissionModel->getAllPermissions();
    }

    public function showPermission(int $id): ?array
    {
        return $this->permissionModel->getPermissionById($id);
    }

    public function storePermission(string $description)
    {
        return $this->permissionModel->createPermission($description);
    }

    public function updatePermission(int $id, string $description): bool
    {
        return $this->permissionModel->updatePermission($id, $description);
    }

    public function destroyPermission(int $id): bool
    {
        return $this->permissionModel->deletePermission($id);
    }
}
