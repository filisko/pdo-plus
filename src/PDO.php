<?php
namespace Filisko\PDOplus;

use PDO as NativePdo;

class PDO extends NativePdo
{
    /**
     * @var array
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
    public function query($statement, $mode = PDO::ATTR_DEFAULT_FETCH_MODE, $arg3 = null, array $ctorargs = [])
    {
        $start = microtime(true);
        $result = parent::query($statement, $mode, $arg3, $ctorargs);
        $this->addLog($statement, microtime(true) - $start);
        return $result;
    }

    /**
     * Add query to logged queries.
     *
     * @param string $statement
     * @param float $time Elapsed seconds with microseconds
     * @return void
     */
    public function addLog($statement, $time)
    {
        $query = [
            'statement' => $statement,
            'time' => $time * 1000
        ];

        $this->log[] = $query;
    }

    /**
     * Return logged queries.
     *
     * @return array<array{statement:string, time:float}>
     */
    public function getLog()
    {
        return $this->log;
    }
}
