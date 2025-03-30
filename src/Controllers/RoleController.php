<?php

class RoleController
{
    private $roleModel;

    public function __construct(RoleModel $roleModel)
    {
        $this->roleModel = $roleModel;
    }

    public function createRole(string $roleName): void
    {
        $this->roleModel->addRole($roleName);
    }

    public function deleteRole(string $roleName): void
    {
        $this->roleModel->removeRole($roleName);
    }

    public function listRoles(): array
    {
        return $this->roleModel->getAllRoles();
    }
}
