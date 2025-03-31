<?php
namespace App\Models;

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Database.php';

use App\Models\Database;


class WishlistModel extends Model
{
    public function __construct($connection = null)
    {
        if (is_null($connection)) {
            $this->connection = new Database();
        } else {
            $this->connection = $connection;
        }
    }

    public function addOfferToWishlist(int $userId, int $offerId): int
    {
        $record = [
            'ID_USER'  => $userId,
            'ID_OFFER' => $offerId
        ];

        return $this->connection->insertRecord("love", $record);
    }

    public function removeOfferFromWishlist(int $userId, int $offerId): int
    {
        $pdo = $this->connection->getPdo();
        $sql = "DELETE FROM love WHERE ID_USER = :u AND ID_OFFER = :o";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':u' => $userId,
            ':o' => $offerId
        ]);
        return $stmt->rowCount();
    }

    public function getWishlist(int $userId): array
    {
        $pdo = $this->connection->getPdo();
        $sql = "SELECT * FROM love WHERE ID_USER = :u";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':u' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
