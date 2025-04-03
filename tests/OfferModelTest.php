<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\OfferModel;
require_once 'src/Models/OfferModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';
use PDOStatement; 
use Exception;


class OfferModelTest extends TestCase
{
    private $mockDatabase;
    private $offerModel;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->offerModel = new OfferModel($this->mockDatabase);
    }

    public function testGetAllOffers()
    {
        $expectedOffers = [
            ['ID' => 1, 'TITLE' => 'Offer1', 'CITY' => 'Paris'],
            ['ID' => 2, 'TITLE' => 'Offer2', 'CITY' => 'Lyon']
        ];

        $this->mockDatabase
            ->method('getAllRecords')
            ->with('Offers', 'JOIN Companies ON Offers.ID_COMPANY = Companies.ID')
            ->willReturn($expectedOffers);

        $result = $this->offerModel->getAllOffers();
        $this->assertEquals($expectedOffers, $result);
    }

    public function testGetOffersWhenNoFilters()
    {
        $filters = ['', '', ''];

        $this->mockDatabase
            ->method('getAllRecords')
            ->with('Offers', 'JOIN Companies ON Offers.ID_COMPANY = Companies.ID')
            ->willReturn([['ID' => 1, 'TITLE' => 'Offer1']]);

        $result = $this->offerModel->getOffersWhen($filters);
        $this->assertCount(1, $result);
        $this->assertEquals(1, $result[0]['ID']);
    }
    public function testGetOffersWhenWithFilters()
    {
        $filters = ['Dev', '6', 'Master'];

        $expectedRecords = [
            ['ID' => 10, 'TITLE' => 'Dev Stage', 'GRADE' => 'Master']
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('getRecordsWhen')
            ->with(
                'Offers',
                $this->stringContains("TITLE LIKE :keywords"), 
                "JOIN Companies ON Offers.id_company = Companies.id",
                $this->arrayHasKey(':keywords')
            )
            ->willReturn($expectedRecords);

        $result = $this->offerModel->getOffersWhen($filters);
        $this->assertEquals($expectedRecords, $result);
    }

    public function testGetOffer()
    {
        $offerId = 5;
        $expectedOffer = ['ID' => 5, 'TITLE' => 'Offer5'];

        $this->mockDatabase
            ->method('getRecord')
            ->with(
                'Offers',
                $offerId,
                'JOIN Companies ON Offers.ID_COMPANY = Companies.ID',
                '*'
            )
            ->willReturn($expectedOffer);

        $result = $this->offerModel->getOffer($offerId);
        $this->assertEquals($expectedOffer, $result);
    }

    public function testCreateOfferSuccess()
    {
        $record = [
            'TITLE'         => 'Dev Stage',
            'RELEASE_DATE'  => '2023-01-01',
            'CITY'          => 'Paris',
            'GRADE'         => 'Master',
            'BEGIN_DATE'    => '2023-02-01',
            'DURATION'      => 6,
            'RENUMBER'      => 800,
            'DESCRIPTION'   => 'Stage en dev',
            'ID_COMPANY'    => 2
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('insertRecord')
            ->with('Offers', $record)
            ->willReturn(123);

        $result = $this->offerModel->createOffer(
            $record['TITLE'],
            $record['RELEASE_DATE'],
            $record['CITY'],
            $record['GRADE'],
            $record['BEGIN_DATE'],
            $record['DURATION'],
            $record['RENUMBER'],
            $record['DESCRIPTION'],
            $record['ID_COMPANY']
        );
        $this->assertEquals(123, $result);
    }

    public function testCreateOfferMissingField()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Tous les champs sont obligatoires.");

        $this->offerModel->createOffer(
            'Dev Stage', 
            '',          
            'Paris',
            'Master',
            '2023-02-01',
            6,
            800,
            'Desc',
            2
        );
    }

    public function testUpdateOfferSuccess()
    {
        $id = 10;
        $record = [
            'TITLE'        => 'Updated Stage',
            'RELEASE_DATE' => '2023-01-15',
            'CITY'         => 'Marseille',
            'GRADE'        => 'BAC+5',
            'BEGIN_DATE'   => '2023-02-01',
            'DURATION'     => 6,
            'RENUMBER'     => 1000,
            'DESCRIPTION'  => 'Updated desc',
            'ID_COMPANY'   => 3
        ];

        $condition = "ID = :id";
        $paramsCondition = [':id' => $id];

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with('Offers', $record, $condition, $paramsCondition)
            ->willReturn(1);

        $updatedRows = $this->offerModel->updateOffer(
            $id,
            $record['TITLE'],
            $record['RELEASE_DATE'],
            $record['CITY'],
            $record['GRADE'],
            $record['BEGIN_DATE'],
            $record['DURATION'],
            $record['RENUMBER'],
            $record['DESCRIPTION'],
            $record['ID_COMPANY']
        );
        $this->assertEquals(1, $updatedRows);
    }

    public function testUpdateOfferMissingField()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Tous les champs sont obligatoires.");

        $this->offerModel->updateOffer(
            10, 
            'Stage Dev', 
            '2023-01-01',
            'Paris',
            '', 
            '2023-02-01',
            6,
            1000,
            'Desc',
            3
        );
    }

    public function testDeleteOfferSuccess()
    {
        $offerId = 100;
        $fakeOffer = ['ID' => 100, 'TITLE' => 'Stage A'];

        $this->mockDatabase
            ->method('getRecord')
            ->willReturn($fakeOffer);

        $this->mockDatabase
            ->expects($this->once())
            ->method('deleteRecord')
            ->with('Offers', $offerId)
            ->willReturn(1);

        $deletedRows = $this->offerModel->deleteOffer($offerId);
        $this->assertEquals(1, $deletedRows);
    }

    public function testDeleteOfferThrowsIfNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("L'offre n'existe pas.");

        $offerId = 999;

        $this->mockDatabase
            ->method('getRecord')
            ->willReturn(null);

        $this->offerModel->deleteOffer($offerId);
    }
}
