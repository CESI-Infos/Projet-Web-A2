<?php

class ApplicationController
{
    private $applicationModel;

    public function __construct(PDO $db)
    {
        $this->applicationModel = new ApplicationModel($db);
    }
    public function listApplications(): array
    {
        $applications = $this->applicationModel->getAllApplications();
        return $applications;
    }
    public function showApplication(int $id): ?array
    {
        return $this->applicationModel->getApplicationById($id);
    }
    public function storeApplication(int $userId, int $offerId, string $cvPath, string $coverLetter)
    {
        $newId = $this->applicationModel->createApplication($userId, $offerId, $cvPath, $coverLetter);
        return $newId;
    }
    public function updateApplication(int $id, int $userId, int $offerId, string $cvPath, string $coverLetter): bool
    {
        return $this->applicationModel->updateApplication($id, $userId, $offerId, $cvPath, $coverLetter);
    }
    public function destroyApplication(int $id): bool
    {
        return $this->applicationModel->deleteApplication($id);
    }
}
