<?php

class RoleModel
{

    private $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    private function loadData(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $json = file_get_contents($this->file);
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return [];
        }
        return $data;
    }

    private function saveData(array $roles): bool
    {
        try {
            $json = json_encode($roles, JSON_PRETTY_PRINT);
            file_put_contents($this->file, $json);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addRole(string $roleName): bool
    {
        $roles = $this->loadData();

        if (in_array($roleName, $roles)) {
            return false; 
        }

        $roles[] = $roleName;
        return $this->saveData($roles);
    }

    public function removeRole(string $roleName): bool
    {
        $roles = $this->loadData();

        $newRoles = array_filter($roles, function($role) use ($roleName) {
            return $role !== $roleName;
        });

        if (count($roles) === count($newRoles)) {
            return false;
        }

        return $this->saveData(array_values($newRoles));
    }

    public function getAllRoles(): array
    {
        return $this->loadData();
    }

    public function roleExists(string $roleName): bool
    {
        $roles = $this->loadData();
        return in_array($roleName, $roles);
    }
}
