<?php

class WishListModel
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

    private function saveData(array $data): bool
    {
        try {
            $json = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents($this->file, $json);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function addOfferToWishlist(int $userId, int $offerId): bool
    {
        $data = $this->loadData();

        if (!isset($data[$userId])) {
            $data[$userId] = [];
        }

        if (!in_array($offerId, $data[$userId])) {
            $data[$userId][] = $offerId;
        }

        return $this->saveData($data);
    }

    public function removeOfferFromWishlist(int $userId, int $offerId): bool
    {
        $data = $this->loadData();

        if (isset($data[$userId])) {
            $data[$userId] = array_filter($data[$userId], function($id) use ($offerId) {
                return $id !== $offerId;
            });
        }

        return $this->saveData($data);
    }
    
    public function getWishlist(int $userId): array
    {
        $data = $this->loadData();
        return $data[$userId] ?? [];
    }
}
