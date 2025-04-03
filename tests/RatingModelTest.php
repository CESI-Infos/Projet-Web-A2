<?php
namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Models\RatingModel;
require_once 'src/Models/RatingModel.php';
use App\Models\Database;
require_once 'src/Models/Database.php';

class RatingModelTest extends TestCase
{
    private $mockDatabase;
    private $ratingModel;

    protected function setUp(): void
    {
        $this->mockDatabase = $this->createMock(Database::class);
        $this->ratingModel = new RatingModel($this->mockDatabase);
    }

    public function testGetAllNotes()
    {
        $expectedNotes = [
            ['ID_COMPANY' => 1, 'GRADE' => 4],
            ['ID_COMPANY' => 2, 'GRADE' => 5],
        ];

        $this->mockDatabase
            ->method('getAllRecords')
            ->with('Note')
            ->willReturn($expectedNotes);

        $result = $this->ratingModel->getAllNotes();
        $this->assertEquals($expectedNotes, $result);
    }

    public function testCreateNote()
    {
        $idCompany = 10;
        $idUser = 42;
        $note = 5;

        $expectedRecord = [
            'ID_COMPANY' => $idCompany,
            'ID_USER' => $idUser,
            'GRADE' => $note,
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('insertRecord')
            ->with('Note', $expectedRecord)
            ->willReturn(123);

        $result = $this->ratingModel->createNote($idCompany, $idUser, $note);

        $this->assertEquals(123, $result);
    }

    public function testUpdateNote()
    {
        $idCompany = 10;
        $idUser = 42;
        $note = 3;

        $expectedRecord = [
            'ID_COMPANY' => $idCompany,
            'ID_USER' => $idUser,
            'GRADE' => $note,
        ];

        $this->mockDatabase
            ->expects($this->once())
            ->method('updateRecord')
            ->with(
                'Note',
                $expectedRecord,
                "ID_COMPANY = '$idCompany' AND ID_USER = '$idUser'"
            )
            ->willReturn(1);

        $result = $this->ratingModel->updateNote($idCompany, $idUser, $note);
        $this->assertEquals(1, $result);
    }
}
