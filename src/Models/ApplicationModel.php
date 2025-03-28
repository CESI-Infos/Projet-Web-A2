<?php

class ApplicationModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }
    public function getAllApplications(): array
    {
        try {
            $sql = "SELECT * FROM applications";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    public function getApplicationById(int $id): ?array
    {
        try {
            $sql = "SELECT * FROM applications WHERE ID = :ID";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
            $stmt->execute();

            $application = $stmt->fetch(PDO::FETCH_ASSOC);
            return $application ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
    public function createApplication(int $userId, int $offerId, string $cvPath, string $coverLetter)
    {
        try {
            $sql = "INSERT INTO applications (ID_USER, ID_OFFER, CV, LETTER)
                    VALUES (:ID_USER, :ID_OFFER, :CV, :LETTER)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ID_USER', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':ID_OFFER', $offerId, PDO::PARAM_INT);
            $stmt->bindValue(':CV', $cvPath, PDO::PARAM_STR);
            $stmt->bindValue(':LETTER', $coverLetter, PDO::PARAM_STR);

            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            echo " Erreur PDO : " . $e->getMessage();
            return false;
        }
    }
    public function updateApplication(int $id, int $userId, int $offerId, string $cvPath, string $coverLetter): bool
    {
        try {
            $sql = "UPDATE applications
                    SET ID_USER = :ID_USER,
                        ID_OFFER = :ID_OFFER,
                        CV = :CV,
                        LETTER = :LETTER,
                        RELEASE_DATE = CURDATE()
                    WHERE ID = :ID";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ID_USER', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':ID_OFFER', $offerId, PDO::PARAM_INT);
            $stmt->bindValue(':CV', $cvPath, PDO::PARAM_STR);
            $stmt->bindValue(':LETTER', $coverLetter, PDO::PARAM_STR);
            $stmt->bindValue(':ID', $id, PDO::PARAM_INT);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur PDO UPDATE : " . $e->getMessage();
            return false;
        }
    }
    public function deleteApplication(int $id): bool
    {
        try {
            $sql = "DELETE FROM applications WHERE ID = :ID";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':ID', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}
