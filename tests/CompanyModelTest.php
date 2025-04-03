<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\CompanyModel;
require_once 'src/Models/CompanyModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php'; 

class CompanyModelTest extends TestCase
{
    private $mockDatabase;
    private $companyModel;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(Database::class);

        $this->companyModel = new CompanyModel($this->mockDatabase);
    }

    public function testGetAllCompanies()
    {
        $expectedCompanies = [
            ['ID' => 1, 'NAME' => 'Company A'],
            ['ID' => 2, 'NAME' => 'Company B'],
        ];

        $this->mockDatabase
            ->method('getAllRecords')
            ->with('Companies')
            ->willReturn($expectedCompanies);

        $result = $this->companyModel->getAllCompanies();
        $this->assertEquals($expectedCompanies, $result);
    }

    public function testGetCompany()
    {
        $companyId = 10;
        $expectedCompany = ['ID'=>10, 'NAME'=>'Test Corp'];

        $this->mockDatabase
            ->method('getRecord')
            ->with('Companies', $companyId)
            ->willReturn($expectedCompany);

        $result = $this->companyModel->getCompany($companyId);
        $this->assertEquals($expectedCompany, $result);
    }

    public function testCreateCompany()
    {
        $name        = 'New Company';
        $description = 'Description';
        $mail        = 'contact@newco.com';
        $phone       = '0123456789';

        $record = [
            'NAME'        => $name,
            'DESCRIPTION' => $description,
            'MAIL'        => $mail,
            'PHONE'       => $phone,
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('insertRecord')
            ->with('Companies', $record)
            ->willReturn(123);

        $insertId = $this->companyModel->createCompany($name, $description, $mail, $phone);
        $this->assertEquals(123, $insertId);
    }

    public function testUpdateCompanySuccess()
    {
        $companyId   = 5;
        $name        = 'Updated Company';
        $description = 'Updated Description';
        $mail        = 'updated@co.com';
        $phone       = '0987654321';

        $record = [
            'NAME'        => $name,
            'DESCRIPTION' => $description,
            'MAIL'        => $mail,
            'PHONE'       => $phone,
        ];
        $condition = "id = :id";
        $paramsCondition = [':id' => $companyId];

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with('Companies', $record, $condition, $paramsCondition)
            ->willReturn(1);

        $this->expectOutputString("Modification réussie.");

        $this->companyModel->updateCompany($companyId, $name, $description, $mail, $phone);

    }

    public function testUpdateCompanyFail()
    {
        $companyId   = 6;
        $name        = 'Fail Inc';
        $description = 'Fail Desc';
        $mail        = 'fail@co.com';
        $phone       = '111222333';

        $record = [
            'NAME'        => $name,
            'DESCRIPTION' => $description,
            'MAIL'        => $mail,
            'PHONE'       => $phone,
        ];
        $condition = "id = :id";
        $paramsCondition = [':id' => $companyId];

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with('Companies', $record, $condition, $paramsCondition)
            ->willReturn(0);

        $this->expectOutputString("Erreur lors de la modification.");

        $this->companyModel->updateCompany($companyId, $name, $description, $mail, $phone);

    }

    public function testDeleteCompany()
    {
        $companyId = 1;

        $this->mockDatabase
            ->expects($this->once())
            ->method('deleteRecord')
            ->with('Companies', $companyId)
            ->willReturn(1);

        $deletedRows = $this->companyModel->deleteCompany($companyId);
        $this->assertEquals(1, $deletedRows);
    }
}
