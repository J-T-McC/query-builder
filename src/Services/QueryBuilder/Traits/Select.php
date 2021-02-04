<?php

namespace Database\Services\QueryBuilder\Traits;

use Database\Services\QueryBuilder\Builder;

trait Select
{

    /**
     * @param mixed ...$columns
     *
     * @return $this
     */
    public function select(...$columns)
    {
        $this->parts['select'] = static::testArrayOfStrings($columns);

        return $this;
    }

    /**
     * @param mixed ...$columns
     *
     * @return $this
     */
    public function selectRaw(...$columns)
    {
        $this->parts['selectRaw'] = $columns;

        return $this;
    }

    /**
     * @param $column
     *
     * @return $this
     */
    public function groupBy($column)
    {
        $this->parts['groupBy'][] = self::testString($column);

        return $this;
    }

    /**
     * @param        $column
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy($column, $direction = 'ASC')
    {
        $this->parts['orderBy'][] = self::testString($column) . ' ' . $direction;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->parts['limit'] = $limit;

        return $this;
    }

    private function buildSelect()
    {
        $select = implode(', ', array_merge($this->parts['select'], $this->parts['selectRaw']));

        if (empty($select)) {
            $select = ' * ';
        }

        $joins = implode(' ', $this->parts['joins']);

        $conditions = trim(self::buildWhereConditions($this->parts['where']));
        $where = ! empty($conditions) ? "WHERE {$conditions}" : '';
        $groupBy = self::buildCondition('GROUP BY %s', ', ', $this->parts['groupBy']);

        $this->query = sprintf(
            "SELECT %s FROM %s %s %s %s",
            $select,
            $this->table,
            $joins,
            $where,
            $groupBy,
        );

        $this->applyUnions();

        $orderBy = self::buildCondition('ORDER BY %s', ', ', $this->parts['orderBy']);

        $limit = !is_null($this->parts['limit']) ? "LIMIT {$this->parts['limit']}" : '';

        $this->query .= sprintf("%s %s", $orderBy, $limit);
    }

    /**
     * @param string $format
     * @param string $delimiter
     * @param array  $items
     *
     * @return string
     */
    private static function buildCondition(string $format, string $delimiter, array $items): string
    {
        return count($items) ? sprintf($format, implode($delimiter, $items)) : '';
    }

}