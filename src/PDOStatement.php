<?php
namespace Filisko\PDOplus;

class PDOStatement
{
    /**
     * PDO instance.
     * @var \PDO
     */
    protected $pdo;

    /**
     * Original PDOStatement instance.
     * @var \PDOStatement
     */
    protected $PDOStatement;

    /**
     * For binding simulations purposes.
     * @var array
     */
    protected $bindings = [];

    /**
     * Sets the PDO logging class instance and prepared statement.
     *
     * @param Pdo           $pdo       The PDO logging class instance.
     * @param \PDOStatement $statement The original prepared statement.
     */
    public function __construct(\Filisko\PDOplus\PDO $pdo, \PDOStatement $PDOStatement)
    {
        $this->pdo = $pdo;
        $this->PDOStatement = $PDOStatement;
    }

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
            array($this->PDOStatement, $name),
            $arguments
        );
    }

    /**
     * @see \PDOStatement::bindColumn
     */
    public function bindColumn($column, &$param)
    {
        return $this->PDOStatement->bindColumn($column, $param);
    }

    /**
     * @see \PDOStatement::bindParam
     */
    public function bindParam($parameter , &$variable,  $data_type = \PDO::PARAM_STR)
    {
        $this->bindings[$parameter] = $variable;
        return $this->PDOStatement->bindParam($parameter, $variable, $data_type);
    }

    /**
     * @see \PDOStatement::bindValue
     */
    public function bindValue($parameter , &$variable,  $data_type = \PDO::PARAM_STR)
    {
        $this->bindings[$parameter] = $variable;
        return $this->PDOStatement->bindValue($parameter, $variable, $data_type);
    }

    /**
     * @see \PDOStatement::execute
     */
    public function execute($input_parameters = null)
    {
        if (is_array($input_parameters)) {
            $this->bindings = $input_parameters;
        }

        $bindings = $this->bindings;
        $statement = $this->PDOStatement->queryString;
        foreach ($bindings as $param => $value) {
            $value = (is_numeric($value) or is_null($value)) ? $value : $this->pdo->quote($value);
            $value = is_null($value) ? "null" : $value;
            $statement = preg_replace("/$param(?![a-zA-Z])/", $value, $statement);
        }

        $start = microtime(true);
        $result = $this->PDOStatement->execute($input_parameters);
        $this->pdo->addLog($statement, microtime(true) - $start);

        return $result;
    }
}
