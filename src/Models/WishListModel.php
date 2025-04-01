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
    $condition = "ID_USER = :u AND ID_OFFER = :o";
    $params = [
        ':u' => $userId,
        ':o' => $offerId
    ];

    return $this->connection->deleteRecordCondition("love", $condition, $params);
}


public function getWishlist(int $userId): array
{
    return $this->connection->getRecordsWhen(
        "love",
        "ID_USER = :u",
        "JOIN Offers ON love.ID_OFFER = Offers.ID",
        [':u' => $userId],
        "love.ID_OFFER,
         Offers.TITLE,
         Offers.CITY,
         Offers.DURATION,
         Offers.GRADE,
         Offers.ID_COMPANY"
    );
}

}
