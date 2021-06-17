<?php

declare(strict_types=1);

namespace Filisko\PDOplus;

use PDOStatement as NativePdoStatement;

class PDOStatement extends NativePdoStatement
{
    /**
     * PDO instance.
     */
    protected PDO $pdo;

    /**
     * For binding simulations purposes.
     */
    protected array $bindings = [];

    protected function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @inheritDoc
     */
    public function bindParam(
        $param,
        &$var,
        $type = PDO::PARAM_STR,
        $maxLength = null,
        $driverOptions = null
    ) {
        $this->bindings[$param] = $var;
        return parent::bindParam($param, $var, $type, $maxLength, $driverOptions);
    }

    /**
     * @inheritDoc
     */
    public function bindValue($param, $value, $type = PDO::PARAM_STR)
    {
        $this->bindings[$param] = $value;
        return parent::bindValue($param, $value, $type);
    }

    /**
     * @inheritDoc
     */
    public function execute($params = null)
    {
        if (is_array($params)) {
            $this->bindings = $params;
        }

        $start  = microtime(true);
        $result = parent::execute($params);

        $this->pdo->addLog(
            $this->getStatementWithBindingsIn($this->bindings, $this->queryString),
            microtime(true) - $start
        );

        return $result;
    }

    private function getStatementWithBindingsIn(array $bindings, string $query)
    {
        $indexed = ($bindings == array_values($bindings));

        foreach ($bindings as $param => $value) {
            $value = is_numeric($value) || is_bool($value) || $value === null ? $value : $this->pdo->quote($value);
            $value = is_null($value) ? 'null' : $value;

            if ($indexed) {
                $query = preg_replace('/\?/', (string)$value, $query, 1);
            } else {
                $query = str_replace(":$param", (string)$value, $query);
            }
        }

        return $query;
    }
}
