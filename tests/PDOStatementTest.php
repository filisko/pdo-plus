<?php

use Filisko\PDOplus\PDO as CustomPDO;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class PDOStatementTest extends TestCase
{
    private $customPdo;

    private $prophet;

    public function setUp(): void
    {
        $this->customPdo = new CustomPDO('sqlite::memory:');
        $this->prophet = new Prophet;
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    /**
    * @dataProvider dataForTestAddValuesToQuery
    */
    public function testAddValuesToQuery($bindings, $query, $expected)
    {
        $this->customPdo->exec('CREATE TABLE users(id INTEGER,name TEXT, surname TEXT);');
        $pdoStatement = $this->customPdo->prepare($query);


        $result = $pdoStatement->addValuesToQuery($bindings, $query);
        $this->assertEquals($expected, $result);
    }

    public function dataForTestAddValuesToQuery()
    {
        return [
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
}
