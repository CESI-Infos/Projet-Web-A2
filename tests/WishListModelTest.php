<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\WishlistModel;
require_once 'src/Models/WishlistModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';
use PDOStatement; 


class WishlistModelTest extends TestCase
{
    private $mockDatabase;
    private $wishlistModel;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->wishlistModel = new WishlistModel($this->mockDatabase);
    }

    public function testAddOfferToWishlist()
    {
        $userId = 10;
        $offerId = 42;

        $expectedRecord = [
            'ID_USER'  => $userId,
            'ID_OFFER' => $offerId
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('insertRecord')
            ->with('love', $expectedRecord)
            ->willReturn(123);

        $result = $this->wishlistModel->addOfferToWishlist($userId, $offerId);

        $this->assertEquals(123, $result);
    }

    public function testRemoveOfferFromWishlist()
    {
        $userId = 10;
        $offerId = 42;

        $condition = "ID_USER = :u AND ID_OFFER = :o";
        $params = [':u' => $userId, ':o' => $offerId];

        $this->mockDatabase
            ->expects($this->once())
            ->method('deleteRecordCondition')
            ->with('love', $condition, $params)
            ->willReturn(1);

        $deletedRows = $this->wishlistModel->removeOfferFromWishlist($userId, $offerId);
        $this->assertEquals(1, $deletedRows);
    }

    public function testGetOfferInWishlist()
    {
        $userId = 10;
        $offerId = 42;

        $expectedRow = [
            'ID_USER'  => $userId,
            'ID_OFFER' => $offerId
        ];

        $mockStatement = $this->createMock(PDOStatement::class);

        $mockStatement->method('fetch')
            ->willReturn($expectedRow);

        $mockStatement->expects($this->once())
            ->method('execute')
            ->with([
                'userId'  => $userId,
                'offerId' => $offerId
            ]);

        $this->mockDatabase->method('prepare')
            ->willReturn($mockStatement);

        $result = $this->wishlistModel->getOfferInWishlist($userId, $offerId);
        $this->assertEquals($expectedRow, $result);
    }
}
