<?php

declare(strict_types=1);

use Filisko\PDOplus\PDO as PDOplus;
use Filisko\PDOplus\PDOStatement as PdoPlusStatement;
use PHPUnit\Framework\TestCase;

class PDOTest extends TestCase
{
    private $sut;

    public function setUp(): void
    {
        $this->sut = new PDOplus('sqlite::memory:');
        $this->sut->exec('CREATE TABLE users(id INTEGER,name TEXT, surname TEXT);');
    }

    public function tearDown(): void
    {
        if ($this->sut->exec('DROP TABLE users;') !== 0) {
            throw new RuntimeException('Table not dropped?');
        }
    }

    public function testExec()
    {
        $this->sut->exec("SELECT * FROM users WHERE `name` = 'Filis'");

        static::assertEquals(
            "SELECT * FROM users WHERE `name` = 'Filis'",
            $this->sut->getLog()[1]['statement']
        );
    }

    public function testQuery()
    {
        $result = $this->sut->query("SELECT * FROM users WHERE `name` = 'Filis'", PDO::FETCH_ASSOC);

        static::assertInstanceOf(PdoPlusStatement::class, $result);
        static::assertEquals(
            "SELECT * FROM users WHERE `name` = 'Filis'",
            $this->sut->getLog()[1]['statement']
        );
    }

    public function testFetchAll()
    {
        $stmt =$this->sut->query("SELECT * FROM users WHERE `name` = 'Filis'");
        $data = $stmt->fetchAll(PDO::FETCH_CLASS, "User");
        $stmt->closeCursor();

        static::assertInstanceOf(PdoPlusStatement::class, $stmt);
        static::assertEquals(
            "SELECT * FROM users WHERE `name` = 'Filis'",
            $this->sut->getLog()[1]['statement']
        );

    }
}


class User
{
    public $name;
    public $surname;
}