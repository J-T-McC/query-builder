<?php

namespace Database\Services\QueryBuilder\Traits;

use Database\Exceptions\InvalidArgumentException;

trait Join
{

    /**
     * @param $table
     * @param $key
     * @param $foreignKey
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function innerJoin($table, $key, $foreignKey)
    {
        $this->join($table, $key, $foreignKey, 'INNER');

        return $this;
    }

    /**
     * @param $table
     * @param $key
     * @param $foreignKey
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function leftJoin($table, $key, $foreignKey)
    {
        $this->join($table, $key, $foreignKey, 'LEFT');

        return $this;
    }

    /**
     * @param $table
     * @param $key
     * @param $foreignKey
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function rightJoin($table, $key, $foreignKey)
    {
        $this->join($table, $key, $foreignKey, 'RIGHT');

        return $this;
    }

    /**
     * @param $table
     *
     * @return $this
     */
    public function crossJoin($table) {
        $this->parts['joins'][] = sprintf('CROSS JOIN %s', $table);

        return $this;
    }


    /**
     * @param        $table
     * @param        $key
     * @param        $foreignKey
     * @param string $type
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function join($table, $key, $foreignKey, $type = 'INNER')
    {

        if(!in_array($type, static::joins)) {
            throw new InvalidArgumentException('Invalid join specified');
        }

        $this->parts['joins'][] = sprintf(
            '%s JOIN %s ON %s.%s = %s.%s', ...self::testArrayOfStrings([
            $type,
            $table,
            $this->table,
            $key,
            $table,
            $foreignKey,
        ]));

        return $this;
    }

}