<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\UserModel;
require_once 'src/Models/UserModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';
use PDOStatement;


class UserModelTest extends TestCase
{
    private $mockDatabase;
    private $userModel;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->userModel = new UserModel($this->mockDatabase);
    }

    public function testAddUserSuccess()
    {
        $firstname = 'John';
        $lastname  = 'Doe';
        $mail      = 'john@example.com';
        $password  = 'secret';
        $idRole    = 1;
        $idPilote  = null; 

        $this->mockDatabase
            ->expects($this->once())
            ->method('insertRecord')
            ->with(
                'Users',
                $this->callback(function($params) use ($firstname, $lastname, $mail, $idRole) {
                    return $params['firstname'] === $firstname
                        && $params['lastname']  === $lastname
                        && $params['mail']      === $mail
                        && $params['id_role']   === $idRole
                        && isset($params['password']); 
                })
            )
            ->willReturn(123);

        $userId = $this->userModel->addUser($firstname, $lastname, $mail, $password, $idRole, $idPilote);
        $this->assertEquals(123, $userId);
    }

    public function testEditUserAllFields()
    {
        $id = 10;
        $firstname = 'Jane';
        $lastname  = 'Smith';
        $mail      = 'jane@example.com';
        $password  = 'newpass';
        $idRole    = 2;
        $idPilote  = 5;

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with(
                'Users',
                $this->callback(function($data) {
                    return array_key_exists('firstname', $data)
                        && array_key_exists('lastname', $data)
                        && array_key_exists('mail', $data)
                        && array_key_exists('password', $data)
                        && array_key_exists('id_role', $data)
                        && array_key_exists('id_pilote', $data);
                }),
                'id = :id',
                [':id' => $id]
            )
            ->willReturn(1);

        $rowCount = $this->userModel->editUser($id, $firstname, $lastname, $mail, $password, $idRole, $idPilote);
        $this->assertEquals(1, $rowCount);
    }

    public function testEditUserPartial()
    {
        $id = 11;
        $mail = 'partial@example.com';

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with(
                'Users',
                ['mail' => $mail],
                'id = :id',
                [':id' => $id]
            )
            ->willReturn(1);

        $rowCount = $this->userModel->editUser($id, null, null, $mail, null, null, null);
        $this->assertEquals(1, $rowCount);
    }

    public function testDeleteUser()
    {
        $id = 99;
        $condition = 'id = :id';
        $params = [':id' => $id];

        $this->mockDatabase
            ->expects($this->once())
            ->method('deleteRecordCondition')
            ->with('Users', $condition, $params)
            ->willReturn(1);

        $deletedRows = $this->userModel->deleteUser($id);
        $this->assertEquals(1, $deletedRows);
    }

    public function testAuthenticateSuccess()
    {
        $mail     = 'john@example.com';
        $password = 'secret';

        $fakedUser = [
            'ID'       => 1,
            'MAIL'     => $mail,
            'PASSWORD' => password_hash($password, PASSWORD_DEFAULT),
            'FIRSTNAME'=> 'John'
        ];

        $this->mockDatabase
            ->method('getRecordsWhen')
            ->with('Users', 'mail = :mail', '', [':mail' => $mail])
            ->willReturn([$fakedUser]);

        $result = $this->userModel->authenticate($mail, $password);
        $this->assertEquals($fakedUser, $result);
    }

    public function testAuthenticateFailNotFound()
    {
        $mail     = 'unknown@example.com';
        $password = 'secret';

        $this->mockDatabase
            ->method('getRecordsWhen')
            ->willReturn([]);

        $result = $this->userModel->authenticate($mail, $password);
        $this->assertNull($result);
    }

    public function testAuthenticateFailWrongPassword()
    {
        $mail     = 'john@example.com';
        $password = 'wrongpass';

        $fakedUser = [
            'ID'       => 1,
            'MAIL'     => $mail,
            'PASSWORD' => password_hash('secret', PASSWORD_DEFAULT)
        ];

        $this->mockDatabase
            ->method('getRecordsWhen')
            ->willReturn([$fakedUser]);

        $result = $this->userModel->authenticate($mail, $password);
        $this->assertNull($result);
    }

    public function testGetAllUsersNoKeywords()
    {
        $mockUsers = [
            ['ID'=>1, 'MAIL'=>'a@example.com'],
            ['ID'=>2, 'MAIL'=>'b@example.com']
        ];

        $this->mockDatabase
            ->method('getAllRecords')
            ->with('Users')
            ->willReturn($mockUsers);

        $result = $this->userModel->getAllUsers('');
        $this->assertEquals($mockUsers, $result);
    }

    public function testGetAllUsersWithKeywords()
    {
        $keywords = 'search';
        $mockUsers = [['ID'=>3, 'MAIL'=>'search@example.com']];

        $this->mockDatabase
            ->expects($this->once())
            ->method('getRecordsWhen')
            ->with(
                'Users',
                $this->stringContains('firstname LIKE :keywords'),
                '',
                [':keywords' => '%search%']
            )
            ->willReturn($mockUsers);

        $result = $this->userModel->getAllUsers($keywords);
        $this->assertEquals($mockUsers, $result);
    }

    public function testGetAllUsersFromPiloteNoKeywords()
    {
        $idPilote = 50;
        $expected = [
            ['ID'=>5, 'FIRSTNAME'=>'Sam', 'ID_PILOTE'=>50],
            ['ID'=>6, 'FIRSTNAME'=>'Sue', 'ID_PILOTE'=>50]
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('getRecordsWhen')
            ->with('Users', 'id_pilote = :id_pilote', '', [':id_pilote' => $idPilote])
            ->willReturn($expected);

        $result = $this->userModel->getAllUsersFromPilote($idPilote, '');
        $this->assertEquals($expected, $result);
    }

    public function testGetAllUsersFromPiloteWithKeywords()
    {
        $idPilote = 51;
        $keywords = 'doe';
        $expected = [
            ['ID'=>7, 'FIRSTNAME'=>'John', 'ID_PILOTE'=>51, 'MAIL'=>'john@doe.com']
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('getRecordsWhen')
            ->with(
                'Users',
                $this->stringContains('id_pilote = :id_pilote'),
                '',
                $this->arrayHasKey(':keywords')
            )
            ->willReturn($expected);

        $result = $this->userModel->getAllUsersFromPilote($idPilote, $keywords);
        $this->assertEquals($expected, $result);
    }

    public function testGetUserById()
    {
        $userId = 8;
        $fakeUser = [['ID'=>8, 'MAIL'=>'eight@example.com']];

        $this->mockDatabase
            ->expects($this->once())
            ->method('getRecordsWhen')
            ->with('Users', 'id = :id', '', [':id'=>8])
            ->willReturn($fakeUser);

        $result = $this->userModel->getUserById($userId);
        $this->assertEquals($fakeUser[0], $result);
    }

    public function testGetAllPilotes()
    {
        $fakePilotes = [
            ['ID'=>2, 'FIRSTNAME'=>'Pilote1', 'ID_ROLE'=>2],
            ['ID'=>3, 'FIRSTNAME'=>'Pilote2', 'ID_ROLE'=>2]
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('getRecordsWhen')
            ->with('Users', 'id_role = :id_role', '', [':id_role'=>2])
            ->willReturn($fakePilotes);

        $result = $this->userModel->getAllPilotes();
        $this->assertEquals($fakePilotes, $result);
    }
}
