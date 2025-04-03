<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\ApplicationModel;
require_once 'src/Models/ApplicationModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';
use PDOStatement;

class ApplicationModelTest extends TestCase
{
    private $mockDatabase;
    private $applicationModel;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->applicationModel = new ApplicationModel($this->mockDatabase);
    }

    public function testGetAllApplications()
    {
        $expectedApplications = [
            ['ID' => 1, 'CV' => 'cv1.pdf', 'LETTER' => 'Cover letter 1'],
            ['ID' => 2, 'CV' => 'cv2.pdf', 'LETTER' => 'Cover letter 2']
        ];

        $this->mockDatabase->method('getAllRecords')
            ->with('Applications')
            ->willReturn($expectedApplications);

        $result = $this->applicationModel->getAllApplications();

        $this->assertEquals($expectedApplications, $result);
    }

    public function testGetApplication()
    {
        $applicationId = 1;
        $expectedApplication = ['ID' => 1, 'CV' => 'cv1.pdf', 'LETTER' => 'Cover letter 1'];

        $this->mockDatabase->method('getRecord')
            ->with('Applications', $applicationId)
            ->willReturn($expectedApplication);

        $result = $this->applicationModel->getApplication($applicationId);
        $this->assertEquals($expectedApplication, $result);
    }

    public function testAddApplication()
    {
        $cv = 'mycv.pdf';
        $coverLetter = 'My cover letter';
        $userId = 10;
        $offerId = 20;
        $today = date('Y/m/d');

        $expectedRecord = [
            'CV'           => $cv,
            'LETTER'       => $coverLetter,
            'ID_USER'      => $userId,
            'ID_OFFER'     => $offerId,
            'RELEASE_DATE' => $today,
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('insertRecord')
            ->with('Applications', $expectedRecord)
            ->willReturn(123);

        $resultId = $this->applicationModel->addApplication($cv, $coverLetter, $userId, $offerId);

        $this->assertEquals(123, $resultId);
    }

    public function testUpdateApplication()
    {
        $applicationId = 1;
        $cv = 'updatedCV.pdf';
        $coverLetter = 'Updated cover letter';

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with(
                'Applications',
                [
                    'CV' => $cv,
                    'LETTER' => $coverLetter
                ],
                'ID = :id',
                [':id' => $applicationId]
            )
            ->willReturn(1);

        $rowCount = $this->applicationModel->updateApplication($applicationId, $cv, $coverLetter);
        $this->assertEquals(1, $rowCount);
    }

    public function testGetOffersAppliedByUser()
    {
        $userId = 42;

        $expectedOffers = [
            [
                'ID'       => 10,
                'TITLE'    => 'Stage Dev',
                'NAME'     => 'Company A'
            ],
            [
                'ID'       => 11,
                'TITLE'    => 'Stage Marketing',
                'NAME'     => 'Company B'
            ]
        ];

        $mockStatement = $this->createMock(PDOStatement::class);

        $mockStatement->method('fetchAll')
            ->willReturn($expectedOffers);

        $this->mockDatabase->method('prepare')
            ->willReturn($mockStatement);

        $mockStatement->expects($this->once())
            ->method('execute')
            ->with();   

        $result = $this->applicationModel->getOffersAppliedByUser($userId);

        $this->assertEquals($expectedOffers, $result);
    }
}
