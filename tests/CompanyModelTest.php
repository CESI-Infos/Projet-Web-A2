<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\CompanyModel;
require_once 'src/Models/CompanyModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';

class CompanyModelTest extends TestCase {
    private $mockDatabase;
    private $companyModel;

    protected function setUp(): void {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->companyModel = new CompanyModel($this->mockDatabase);
    }

    public function testGetAllCompanies() {
        $expectedCompanies = [
            ['ID' => 1, 'NAME' => 'Company 1'],
            ['ID' => 2, 'NAME' => 'Company 2']
        ];

        $this->mockDatabase->method('getAllRecords')
            ->with('Companies')
            ->willReturn($expectedCompanies);

        $result = $this->companyModel->getAllCompanies();
        $this->assertEquals($expectedCompanies, $result);
    }

    public function testGetCompany() {
        $companyId = 1;
        $expectedCompany = ['ID' => 1, 'NAME' => 'Company 1'];

        $this->mockDatabase->method('getRecord')
            ->with('Companies', $companyId)
            ->willReturn($expectedCompany);

        $result = $this->companyModel->getCompany($companyId);
        $this->assertEquals($expectedCompany, $result);
    }

    public function testCreateCompany() {
        $companyData = [
            'NAME' => 'New Company',
            'DESCRIPTION' => 'Description',
            'MAIL' => 'company@example.com',
            'PHONE' => '123456789'
        ];

        $this->mockDatabase->expects($this->once())
            ->method('insertRecord')
            ->with('Companies', $companyData)
            ->willReturn(1);

        $result = $this->companyModel->createCompany(
            $companyData['NAME'],
            $companyData['DESCRIPTION'],
            $companyData['MAIL'],
            $companyData['PHONE']
        );

        $this->assertEquals(1, $result);
    }

    public function testUpdateCompany() {
        $companyId = 1;
        $companyData = [
            'NAME' => 'Updated Company',
            'DESCRIPTION' => 'Updated Description',
            'MAIL' => 'updated@example.com',
            'PHONE' => '987654321'
        ];

        $this->mockDatabase->expects($this->once())
            ->method('updateRecord')
            ->with('Companies', $companyData, 'id = :id', [':id' => $companyId])
            ->willReturn(1);

        $result = $this->companyModel->updateCompany(
            $companyId,
            $companyData['NAME'],
            $companyData['DESCRIPTION'],
            $companyData['MAIL'],
            $companyData['PHONE']
        );

        $this->assertEquals(1, $result);
    }

    public function testDeleteCompany() {
        $companyId = 1;

        $this->mockDatabase->expects($this->once())
            ->method('deleteRecord')
            ->with('Companies', $companyId)
            ->willReturn(1);

        $result = $this->companyModel->deleteCompany($companyId);
        $this->assertEquals(1, $result);
    }
}