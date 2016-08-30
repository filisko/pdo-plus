<?php
namespace Filisko\PDOplus;

class PDO extends \PDO
{
    /**
     * Logged queries.
     * @var array
     */
    protected $log = [];

    /**
     * Relay all calls.
     *
     * @param string $name      The method name to call.
     * @param array  $arguments The arguments for the call.
     *
     * @return mixed The call results.
     */
    public function __call($name, array $arguments)
    {
        return call_user_func_array(
            array($this, $name),
            $arguments
        );
    }

    /**
     * @see \PDO::prepare
     */
    public function prepare($statement, $driver_options = [])
    {
        $PDOStatement = parent::prepare($statement, $driver_options);
        $new = new \Filisko\PDOplus\PDOStatement($this, $PDOStatement);
        return $new;
    }

    /**
     * @see \PDO::exec
     */
    public function exec($statement)
    {
        $start = microtime(true);
        $result = parent::exec($statement);
        $this->addLog($statement, microtime(true) - $start);
        return $result;
    }

    /**
     * @see \PDO::query
     */
    public function query($statement)
    {
        $start = microtime(true);
        $result = parent::query($statement);
        $this->addLog($statement, microtime(true) - $start);
        return $result;
    }

    /**
     * Add query to logged queries.
     * @param string $query
     */
    public function addLog($statement, $time)
    {
        $query = [
            'statement' => $statement,
            'time' => $time
        ];
        array_push($this->log, $query);
    }

    /**
     * Return logged queries.
     * @return array Logged queries
     */
    public function getLog()
    {
        return $this->log;
    }
}
