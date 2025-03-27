<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\UserModel;
require_once 'src/Models/UserModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';

class UserModelTest extends TestCase {
    private $mockDatabase;
    private $userModel;

    protected function setUp(): void {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->userModel = new UserModel($this->mockDatabase);
    }

    public function testGetAllUsers() {
        $expectedUsers = [
            ['ID' => 1, 'MAIL' => 'user1@example.com'],
            ['ID' => 2, 'MAIL' => 'user2@example.com']
        ];

        $this->mockDatabase->method('getAllRecords')
            ->with('Users')
            ->willReturn($expectedUsers);

        $result = $this->userModel->getAllUsers();
        $this->assertEquals($expectedUsers, $result);
    }

    public function testGetUser() {
        $userId = 1;
        $expectedUser = ['ID' => 1, 'MAIL' => 'user1@example.com'];

        $this->mockDatabase->method('getRecord')
            ->with('Users', $userId)
            ->willReturn($expectedUser);

        $result = $this->userModel->getUser($userId);
        $this->assertEquals($expectedUser, $result);
    }

    public function testAddUser() {
        $mail = 'newuser@example.com';
        $password = 'password123';
        $expectedRecord = [
            'lastname' => '',
            'firstname' => '',
            'mail' => $mail,
            'password' => $password,
            'phone' => '',
            'cv' => '',
            'letter' => '',
            'status' => ''
        ];

        $this->mockDatabase->expects($this->once())
            ->method('insertRecord')
            ->with('Users', $expectedRecord)
            ->willReturn(1);

        $result = $this->userModel->addUser($mail, $password);
        $this->assertEquals(1, $result);
    }

    public function testUpdateUser() {
        $userId = 1;
        $lastname = 'Doe';
        $firstname = 'John';
        $password = 'newpassword123';
        $mail = 'updateduser@example.com';
        $status = 'active';
        $expectedRecord = [
            'lastname' => $lastname,
            'firstname' => $firstname,
            'password' => $password,
            'mail' => $mail,
            'status' => $status
        ];

        $this->mockDatabase->expects($this->once())
            ->method('updateRecord')
            ->with('Users', $expectedRecord, 'id = :id', [':id' => $userId])
            ->willReturn(1);

        $result = $this->userModel->updateUser($userId, $lastname, $firstname, $password, $mail, $status);
        $this->assertEquals(1, $result);
    }

    public function testDeleteUser() {
        $userId = 1;

        $this->mockDatabase->expects($this->once())
            ->method('deleteRecord')
            ->with('Users', $userId)
            ->willReturn(1);

        $result = $this->userModel->deleteOffer($userId);
        $this->assertEquals(1, $result);
    }
}
