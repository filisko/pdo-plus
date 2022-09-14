<?php

declare(strict_types=1);

namespace Filisko\PDOplus;

use PDO as NativePdo;

class PDO extends NativePdo
{
    /**
     * Logged queries.
     * @var array<array>
     */
    protected $log = [];

    /**
     * @inheritDoc
     */
    public function __construct($dsn, $username = null, $passwd = null, $options = null)
    {
        parent::__construct($dsn, $username, $passwd, $options);
        $this->setAttribute(self::ATTR_STATEMENT_CLASS, [PDOStatement::class, [$this]]);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function exec($statement)
    {
        $start = microtime(true);
        $result = parent::exec($statement);
        $this->addLog($statement, microtime(true) - $start);

        return $result;
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function query($statement, $mode = PDO::FETCH_ASSOC, ...$ctorargs)
    {
        $start = microtime(true);
        $result = parent::query($statement, $mode, ...$ctorargs);

        $this->addLog($statement, microtime(true) - $start);

        return $result;
    }

    /**
     * Add query to logged queries.
     *
     * @param string $statement
     * @param float $time Elapsed seconds with microseconds
     */
    public function addLog(string $statement, float $time): void
    {
        $this->log[] = [
            'statement' => $statement,
            'time' => $time * 1000
        ];
    }

    /**
     * Return logged queries.
     * @return array<array{statement:string, time:float}> Logged queries
     */
    public function getLog(): array
    {
        return $this->log;
    }
}
