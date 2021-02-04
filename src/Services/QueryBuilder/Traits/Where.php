<?php


namespace Database\Services\QueryBuilder\Traits;

use Closure;

use Database\Exceptions\InvalidArgumentException;
use Database\Services\QueryBuilder\Builder;

trait Where
{

    /**
     * @param                           $column
     * @param string|null               $operator
     * @param string|integer|array|null $value
     * @param string                    $delimiter
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function where($column, $operator = null, $value = null, $delimiter = 'AND')
    {

        if (! in_array($delimiter, self::conditionDelimiters)) {
            throw new InvalidArgumentException('Invalid delimiter ' . $delimiter);
        }

        if (is_a($column, Closure::class)) {
            //handle nested statement
            return $this->buildSubCondition($column, $delimiter);
        }

        $column = static::testString($column);

        if (! in_array($operator, self::operators)) {
            throw new InvalidArgumentException('Invalid operator ' . $operator);
        }

        if (is_array($value)) {
            $this->parts['bindings'] = array_merge(
                $this->parts['bindings'],
                $value
            );
            $statement = "{$column} {$operator} (" . implode(',', array_fill(0, count($value), '?')) . ')';
        } else {
            $this->parts['bindings'][] = $value;
            $statement = "{$column} {$operator} ?";
        }

        $this->parts['where'][] = [
            'statement' => $statement,
            'delimiter' => $delimiter,
        ];

        return $this;
    }


    /**
     * @param                           $column
     * @param string|null               $operator
     * @param string|integer|array|null $value
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        return $this->where($column, $operator, $value, 'OR');
    }

    /**
     * @param       $column
     * @param array $values
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function whereIn($column, array $values)
    {
        return $this->where($column, 'IN', $values);
    }

    /**
     * @param       $column
     * @param array $values
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function orWhereIn($column, array $values)
    {
        return $this->orWhere($column, 'IN', $values);
    }

    /**
     * @param       $column
     * @param array $values
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function whereNotIn($column, array $values)
    {
        return $this->where($column, 'NOT IN', $values);
    }

    /**
     * @param       $column
     * @param array $values
     *
     * @return $this
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    public function orWhereNotIn($column, array $values)
    {
        return $this->orWhere($column, 'NOT IN', $values);
    }

    /**
     * @return array
     */
    public function getWhere()
    {
        return $this->parts['where'];
    }


    /**
     * Recursively build query statements
     *
     * @param $conditions
     *
     * @return string
     */
    private static function buildWhereConditions($conditions)
    {

        $finalStatement = '';

        foreach ($conditions as $index => $condition) {

            if (isset($condition['sub'])) {
                $statement = '(' . self::buildWhereConditions($condition['sub']) . ')';
                $delimiter = $condition['delimiter'];
            } else {
                $statement = $condition['statement'];
                $delimiter = $condition['delimiter'];
            }

            $delimiter = $index ? " {$delimiter} " : '';

            $finalStatement .= $delimiter . $statement;
        }

        return $finalStatement;
    }

    /**
     * @param Closure  $closure
     * @param          $delimiter
     *
     * @return $this
     */
    protected function buildSubCondition(Closure $closure, $delimiter)
    {
        $result = call_user_func($closure, new Builder);
        $this->parts['where'][] = ['sub' => $result->getWhere(), 'delimiter' => $delimiter];
        $this->parts['bindings'] = array_merge($this->parts['bindings'], $result->getBindings());

        return $this;
    }

}