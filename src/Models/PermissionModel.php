<?php

class PermissionModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllPermissions(): array
    {
        try {
            $sql = "SELECT * FROM permissions";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur PDO getAll : " . $e->getMessage();
            return [];
        }
    }

    public function getPermissionById(int $id): ?array
    {
        try {
            $sql = "SELECT * FROM permissions WHERE ID = :ID";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
            $stmt->execute();

            $permission = $stmt->fetch(PDO::FETCH_ASSOC);
            return $permission ?: null;
        } catch (PDOException $e) {
            echo "Erreur PDO getById : " . $e->getMessage();
            return null;
        }
    }

    public function createPermission(string $description)
    {
        try {
            $sql = "INSERT INTO permissions (DESCRIPTION) VALUES (:description)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);

            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            echo "Erreur PDO insert : " . $e->getMessage();
            return false;
        }
    }

    public function updatePermission(int $id, string $description): bool
    {
        try {
            $sql = "UPDATE permissions SET DESCRIPTION = :description WHERE ID = :ID";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':ID', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur PDO update : " . $e->getMessage();
            return false;
        }
    }

    public function deletePermission(int $id): bool
    {
        try {
            $sql = "DELETE FROM permissions WHERE ID = :ID";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ID', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur PDO delete : " . $e->getMessage();
            return false;
        }
    }
}
