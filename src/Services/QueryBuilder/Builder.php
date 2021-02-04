<?php

namespace Database\Services\QueryBuilder;

use PDO;
use PDOStatement;

use Database\Exceptions\InvalidArgumentException;

use Database\Drivers\DatabaseDriver;

use Database\Services\QueryBuilder\Traits\Join;
use Database\Services\QueryBuilder\Traits\Where;
use Database\Services\QueryBuilder\Traits\Union;
use Database\Services\QueryBuilder\Traits\Select;
use Database\Services\QueryBuilder\Traits\Execute;

class Builder
{

    use Join, Where, Union, Select, Execute;

    protected PDO $connection;
    protected PDOStatement $stmt;
    protected ?DatabaseDriver $driver = null;

    protected int $fetchStyle = PDO::FETCH_ASSOC;
    protected string $table;

    private array $defaults = [
        'select' => [],
        'selectRaw' => [],
        'update' => [],
        'where' => [],
        'groupBy' => [],
        'orderBy' => [],
        'bindings' => [],
        'joins' => [],
        'unions' => [],
        'limit' => null,
    ];

    private array $parts = [];

    const operators = [
        '=',
        '<>',
        '!=',
        '>',
        '<',
        'IN',
        'NOT IN',
        'REGEXP',
        'IS',
        'LIKE',
        'NOT LIKE',
    ];

    const conditionDelimiters = [
        'AND',
        'OR',
    ];

    const joins = [
        'INNER',
        'RIGHT',
        'LEFT',
        //CROSS join available via method only
    ];

    /**
     * @param \Database\Drivers\DatabaseDriver|null $driver
     */
    public function __construct(DatabaseDriver $driver = null)
    {
        $this->driver = $driver;
    }


    /**
     * @param string $table
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function table(string $table)
    {
        $this->table = static::testString($table);
        $this->resetParts();

        return $this;
    }

    /**
     * Clear stored parts resetting the database interaction
     */
    private function resetParts()
    {
        $this->parts = $this->defaults;
    }

    /**
     * @return array
     */
    public function getBindings()
    {
        return $this->parts['bindings'];
    }

    /**
     * @param $attributes
     */
    private function buildInsert($attributes)
    {
        $columns = array_keys($attributes);
        $values = array_map(fn($column) => ":{$column}", $columns);
        $this->parts['bindings'] = array_combine($values, array_values($attributes));
        $this->query = sprintf(
            'INSERT INTO %s (%s) VALUES(%s);',
            $this->table,
            implode(',', $columns), implode(',', $values)
        );
    }

    /**
     * Test for unexpected characters in strings that will be used for table and column names
     * NOTE: Table and column names will not be secured by the database like conditional values in prepared statements.
     *       This is not a solution for allowing user submitted data to be used as anything outside of conditional
     *       values in prepared statements. Do not use user submitted values for anything outside bound values
     *       ... i.e. do not use for groups, orders, columns, tables ...etc
     *
     * @param $value
     *
     * @return string
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    private static function testString(string $value)
    {
        if (preg_match("/[^A-Za-z0-9_.$*]/", $value)) {
            throw new InvalidArgumentException('Invalid character found in ' . $value);
        }

        return $value;
    }

    /**
     * @param $values
     *
     * @return array
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    private static function testArrayOfStrings(array $values)
    {
        foreach ($values as $value) {
            static::testString($value);
        }

        return $values;
    }

    /**
     * @param int $style
     *
     * @return $this
     */
    public function setFetchStyle(int $style)
    {
        $this->fetchStyle = $style;

        return $this;
    }

}