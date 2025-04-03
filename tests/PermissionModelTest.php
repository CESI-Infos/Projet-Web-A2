<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\PermissionModel;
require_once 'src/Models/PermissionModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';

class PermissionModelTest extends TestCase
{
    private $mockDatabase;
    private $permissionModel;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->permissionModel = new PermissionModel($this->mockDatabase);
    }

    public function testGetAllPermissions()
    {
        $expectedPermissions = [
            ['ID' => 1, 'DESCRIPTION' => 'Permission A'],
            ['ID' => 2, 'DESCRIPTION' => 'Permission B']
        ];

        $this->mockDatabase->method('getAllRecords')
            ->with('permissions')
            ->willReturn($expectedPermissions);

        $result = $this->permissionModel->getAllPermissions();

        $this->assertEquals($expectedPermissions, $result);
    }

    public function testGetPermission()
    {
        $permissionId = 1;
        $expectedPermission = ['ID' => 1, 'DESCRIPTION' => 'Permission A'];

        $this->mockDatabase->method('getRecord')
            ->with('permissions', $permissionId)
            ->willReturn($expectedPermission);

        $result = $this->permissionModel->getPermission($permissionId);
        $this->assertEquals($expectedPermission, $result);
    }

    public function testCreatePermission()
    {
        $description = 'New Permission';
        $record = ['DESCRIPTION' => $description];

        $this->mockDatabase
            ->expects($this->once())
            ->method('insertRecord')
            ->with('permissions', $record)
            ->willReturn(123);

        $resultId = $this->permissionModel->createPermission($description);

        $this->assertEquals(123, $resultId);
    }

    public function testUpdatePermission()
    {
        $permissionId = 10;
        $description = 'Updated Permission';

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with(
                'permissions',
                ['DESCRIPTION' => $description],
                'id = :id',
                [':id' => $permissionId]
            )
            ->willReturn(1);

        $updatedRows = $this->permissionModel->updatePermission($permissionId, $description);
        $this->assertEquals(1, $updatedRows);
    }

    public function testDeletePermission()
    {
        $permissionId = 99;

        $this->mockDatabase
            ->expects($this->once())
            ->method('deleteRecord')
            ->with('permissions', $permissionId)
            ->willReturn(1);

        $deletedRows = $this->permissionModel->deletePermission($permissionId);
        $this->assertEquals(1, $deletedRows);
    }
}
