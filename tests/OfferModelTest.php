<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\OfferModel;
require_once 'src/Models/OfferModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';

class OfferModelTest extends TestCase {
    private $mockDatabase;
    private $offerModel;

    protected function setUp(): void {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->offerModel = new OfferModel($this->mockDatabase);
    }

    public function testGetAllOffers() {
        $expectedOffers = [
            ['ID' => 1, 'TITLE' => 'Offer 1'],
            ['ID' => 2, 'TITLE' => 'Offer 2']
        ];

        $this->mockDatabase->method('getAllRecords')
            ->with('Offers')
            ->willReturn($expectedOffers);

        $result = $this->offerModel->getAllOffers();
        $this->assertEquals($expectedOffers, $result);
    }

    public function testGetOffer() {
        $offerId = 1;
        $expectedOffer = ['ID' => 1, 'TITLE' => 'Offer 1'];

        $this->mockDatabase->method('getRecord')
            ->with('Offers', $offerId)
            ->willReturn($expectedOffer);

        $result = $this->offerModel->getOffer($offerId);
        $this->assertEquals($expectedOffer, $result);
    }

    public function testCreateOffer() {
        $offerData = [
            'TITLE' => 'New Offer',
            'RELEASE_DATE' => '2023-01-01',
            'CITY' => 'City',
            'GRADE' => 'Grade',
            'BEGIN_DATE' => '2023-02-01',
            'DURATION' => 6,
            'RENUMBER' => 1000,
            'DESCRIPTION' => 'Description',
            'ID_COMPANY' => 1
        ];

        $this->mockDatabase->expects($this->once())
            ->method('insertRecord')
            ->with('Offers', $offerData)
            ->willReturn(1);

        $result = $this->offerModel->createOffer(
            $offerData['TITLE'],
            $offerData['RELEASE_DATE'],
            $offerData['CITY'],
            $offerData['GRADE'],
            $offerData['BEGIN_DATE'],
            $offerData['DURATION'],
            $offerData['RENUMBER'],
            $offerData['DESCRIPTION'],
            $offerData['ID_COMPANY']
        );

        $this->assertEquals(1, $result);
    }

    public function testUpdateOffer() {
        $offerId = 1;
        $offerData = [
            'TITLE' => 'Updated Offer',
            'RELEASE_DATE' => '2023-01-01',
            'CITY' => 'City',
            'GRADE' => 'Grade',
            'BEGIN_DATE' => '2023-02-01',
            'DURATION' => 6,
            'RENUMBER' => 1000,
            'DESCRIPTION' => 'Updated Description',
            'ID_COMPANY' => 1
        ];

        $this->mockDatabase->expects($this->once())
            ->method('updateRecord')
            ->with('Offers', $offerData, 'id = :id', [':id' => $offerId])
            ->willReturn(1);

        $result = $this->offerModel->updateOffer(
            $offerId,
            $offerData['TITLE'],
            $offerData['RELEASE_DATE'],
            $offerData['CITY'],
            $offerData['GRADE'],
            $offerData['BEGIN_DATE'],
            $offerData['DURATION'],
            $offerData['RENUMBER'],
            $offerData['DESCRIPTION'],
            $offerData['ID_COMPANY']
        );

        $this->assertEquals(1, $result);
    }

    public function testDeleteOffer() {
        $offerId = 1;

        $this->mockDatabase->expects($this->once())
            ->method('deleteRecord')
            ->with('Offers', $offerId)
            ->willReturn(1);

        $result = $this->offerModel->deleteOffer($offerId);
        $this->assertEquals(1, $result);
    }
}