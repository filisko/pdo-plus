<?php

declare(strict_types=1);

namespace Filisko\PDOplus;

use PDO as NativePdo;

class PDO extends NativePdo
{
    /**
     * Logged queries.
     *
     * @var array<array{statement:string, time:float}> Logged queries
     */
    protected array $log = [];

    /**
     * Control the logging state.
     * @var bool
     */
    protected $loggingEnabled = true;

    /**
     * @inheritDoc
     */
    public function __construct(string $dsn, ?string $username = null, ?string $passwd = null, ?array $options = null)
    {
        parent::__construct($dsn, $username, $passwd, $options);
        $this->setAttribute(self::ATTR_STATEMENT_CLASS, [PDOStatement::class, [$this]]);
    }

    /**
     * @inheritDoc
     */
    public function exec(string $statement): int|false
    {
        $start = microtime(true);
        $result = parent::exec($statement);
        $this->addLog($statement, microtime(true) - $start);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function query(string $query, ?int $fetchMode = PDO::FETCH_ASSOC, ...$ctorargs): PDOStatement|false
    {
        $start = microtime(true);
        $result = parent::query($query, $fetchMode, ...$ctorargs);

        $this->addLog($query, microtime(true) - $start);

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
        if ($this->loggingEnabled) {
            $this->log[] = [
                'statement' => $statement,
                'time' => $time * 1000
            ];
        }
    }

    /**
     * Return logged queries.
     *
     * @return array<array{statement:string, time:float}> Logged queries
     */
    public function getLog(): array
    {
        return $this->log;
    }


    /**
     * Enables query logging.
     */
    public function enableLogging(): void
    {
        $this->loggingEnabled = true;
    }

    /**
     * Disables query logging.
     */
    public function disableLogging(): void
    {
        $this->loggingEnabled = false;
    }
}
