<?php
namespace Filisko\PDOplus;

class PDOStatement extends \PDOStatement
{
    /**
     * PDO instance.
     * @var \PDO
     */
    protected $pdo;

    /**
     * For binding simulations purposes.
     * @var array
     */
    protected $bindings = [];

    /**
     * @param \PDO           $pdo       The PDO logging class instance.
     */
    protected function __construct(\PDO $pdo)
    {
        $this->pdo          = $pdo;
    }

    /**
     * @see \PDOStatement::bindParam
     */
    public function bindParam(
        $parameter, &$variable, $data_type = \PDO::PARAM_STR, $length = null, $driver_options = null
    ) {
        $this->bindings[$parameter] = $variable;
        return parent::bindParam($parameter, $variable, $data_type, $length, $driver_options);
    }

    /**
     * @see \PDOStatement::bindValue
     */
    public function bindValue($parameter, $variable, $data_type = \PDO::PARAM_STR)
    {
        $this->bindings[$parameter] = $variable;
        return parent::bindValue($parameter, $variable, $data_type);
    }

    /**
     * @see \PDOStatement::execute
     */
    public function execute($input_parameters = null)
    {
        if(is_array($input_parameters)) {
            $this->bindings = $input_parameters;
        }

        $statement = $this->addValuesToQuery($this->bindings, $this->queryString);

        $start  = microtime(true);
        $result = parent::execute($input_parameters);
        $this->pdo->addLog($statement, microtime(true) - $start);
        return $result;
    }

    /**
     * @param array         $bindings
     * @param string        $query
     * @return string
     */
    public function addValuesToQuery($bindings, $query)
    {
        $indexed = ($bindings == array_values($bindings));
        foreach($bindings as $param => $value) {
            $value = (is_numeric($value) or $value === null) ? $value : $this->pdo->quote($value);
            $value = is_null($value) ? 'null' : $value;
            if($indexed) {
                $query = preg_replace('/\?/', $value, $query, 1);
            } else {
                $query = str_replace(":$param", $value, $query);
            }
        }

        return $query;
    }
}
