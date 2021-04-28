<?php

declare(strict_types=1);

use Filisko\PDOplus\PDO as PDOplus;
use PHPUnit\Framework\TestCase;

class PDOStatementTest extends TestCase
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

    public function dataForTestAddValuesToQuery()
    {
        return [
            [
                [null],
                'SELECT * FROM users WHERE `name` = ?',
                "SELECT * FROM users WHERE `name` = null"
            ],
            [
                [1],
                'SELECT * FROM users WHERE `id` = ?',
                "SELECT * FROM users WHERE `id` = 1"
            ],
            [
                [1.5],
                'SELECT * FROM users WHERE `id` = ?',
                "SELECT * FROM users WHERE `id` = 1.5"
            ],
            [
                ['Filis'],
                'SELECT * FROM users WHERE `name` = ?',
                "SELECT * FROM users WHERE `name` = 'Filis'"
            ],
            [
                [
                    1, 'Filis'
                ],
                'SELECT * FROM users WHERE `id` = ? AND `name` = ?',
                "SELECT * FROM users WHERE `id` = 1 AND `name` = 'Filis'"
            ],
            [
                [
                    'id' => 10,
                    'surname' => 'Futsarov'
                ],
                'SELECT * FROM users WHERE `id` = :id AND `surname` = :surname',
                "SELECT * FROM users WHERE `id` = 10 AND `surname` = 'Futsarov'"
            ],
            [
                [],
                'SELECT * FROM users',
                "SELECT * FROM users"
            ]
        ];
    }

    /**
     * @dataProvider dataForTestAddValuesToQuery
     */
    public function testExecute(array $bindings, $query, $expected)
    {
        $pdoStatement = $this->sut->prepare($query);

        $pdoStatement->execute($bindings);

        static::assertEquals(
            $expected,
            $this->sut->getLog()[1]['statement']
        );
    }

    public function testBindValue()
    {
        $pdoStatement = $this->sut->prepare('SELECT * FROM users WHERE `id` = :id');
        $pdoStatement->bindValue('id', 1, PDO::PARAM_INT);
        $pdoStatement->execute();

        static::assertEquals(
            "SELECT * FROM users WHERE `id` = 1",
            $this->sut->getLog()[1]['statement']
        );
    }

    public function testBindParam()
    {
        $pdoStatement = $this->sut->prepare('SELECT * FROM users WHERE `id` = :id');
        $value = null;
        $pdoStatement->bindParam('id', $value, PDO::PARAM_NULL, 1);
        $pdoStatement->execute();

        static::assertEquals(
            "SELECT * FROM users WHERE `id` = null",
            $this->sut->getLog()[1]['statement']
        );
    }
}
