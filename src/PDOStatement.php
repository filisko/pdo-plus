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
        int|string $param,
        mixed &$var,
        int $type = PDO::PARAM_STR,
        int $maxLength = 0,
        mixed $driverOptions = null
    ): bool {
        $this->bindings[$param] = $var;
        return parent::bindParam($param, $var, $type, $maxLength, $driverOptions);
    }

    /**
     * @inheritDoc
     */
    public function bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR): bool
    {
        $this->bindings[$param] = $value;
        return parent::bindValue($param, $value, $type);
    }

    /**
     * @inheritDoc
     */
    public function execute(?array $params = null): bool
    {
        if (is_array($params)) {
            $this->bindings = $params;
        }

        $start  = microtime(true);
        $result = parent::execute($params);

        $this->pdo->addLog(
            $this->produceStatementWithBindingsInForLogging($this->bindings, $this->queryString),
            microtime(true) - $start
        );

        return $result;
    }

    private function produceStatementWithBindingsInForLogging(array $bindings, string $query): string
    {
        $indexed = ($bindings == array_values($bindings));

        $result = $query;

        foreach ($bindings as $param => $value) {
            $valueForPresentation = $this->translateValueForPresentationInsideStatement($value);

            if ($indexed) {
                $result = preg_replace('/\?/', $valueForPresentation, $result, 1);
            } else {
                $result = str_replace(":$param", $valueForPresentation, $result);
            }
        }

        return $result;
    }

    private function translateValueForPresentationInsideStatement(mixed $value): string
    {
        $result = $value;

        if ($value === null) {
            $result = 'null';
        } elseif (is_string($value)) {
            $result = $this->pdo->quote($value);
        } elseif (is_bool($value) && $value === false) {
            $result = '0';
        } elseif (is_bool($value) && $value === true) {
            $result = '1';
        }

        return (string)$result;
    }
}
