<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\RoleModel;
require_once 'src/Models/RoleModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';

class RoleModelTest extends TestCase
{
    private $mockDatabase;
    private $roleModel;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(Database::class);

        $this->roleModel = new RoleModel($this->mockDatabase);
    }

    public function testGetAllRoles()
    {
        $expectedRoles = [
            ['ID' => 1, 'NAME' => 'Admin'],
            ['ID' => 2, 'NAME' => 'Pilote'],
            ['ID' => 3, 'NAME' => 'Etudiant']
        ];

        $this->mockDatabase->method('getAllRecords')
            ->with('Roles')
            ->willReturn($expectedRoles);

        $result = $this->roleModel->getAllRoles();

        $this->assertEquals($expectedRoles, $result);
    }

    public function testGetRole()
    {
        $roleId = 2;
        $expectedRole = ['ID' => 2, 'NAME' => 'Pilote'];

        $this->mockDatabase->method('getRecord')
            ->with('Roles', $roleId)
            ->willReturn($expectedRole);

        $result = $this->roleModel->getRole($roleId);
        $this->assertEquals($expectedRole, $result);
    }

    public function testCreateRole()
    {
        $roleName = 'SuperUser';

        $this->mockDatabase
            ->expects($this->once())
            ->method('insertRecord')
            ->with('Roles', ['NAME' => $roleName])
            ->willReturn(123);

        $resultId = $this->roleModel->createRole($roleName);
        $this->assertEquals(123, $resultId);
    }

    public function testUpdateRole()
    {
        $roleId = 5;
        $newName = 'Coordo';

        $record = ['NAME' => $newName];
        $condition = "ID = :id";
        $params = [':id' => $roleId];

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with('Roles', $record, $condition, $params)
            ->willReturn(1);

        $updatedRows = $this->roleModel->updateRole($roleId, $newName);
        $this->assertEquals(1, $updatedRows);
    }

    public function testDeleteRole()
    {
        $roleId = 99;

        $this->mockDatabase
            ->expects($this->once())
            ->method('deleteRecord')
            ->with('Roles', $roleId, 'ID')
            ->willReturn(1);

        $deletedRows = $this->roleModel->deleteRole($roleId);
        $this->assertEquals(1, $deletedRows);
    }
}
