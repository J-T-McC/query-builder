<?php

namespace Database\Services\QueryBuilder\Traits;

use Database\Exceptions\BuilderException;

trait Execute
{

    /**
     * @return array
     * @throws \Database\Exceptions\BuilderException
     */
    public function get()
    {
        $this->buildSelect();
        $this->run();

        return $this->stmt->fetchAll($this->fetchStyle);
    }

    /**
     * @return mixed
     * @throws \Database\Exceptions\BuilderException
     */
    public function first()
    {
        $this->buildSelect();
        $this->run();

        return $this->stmt->fetch($this->fetchStyle);
    }

    /**
     * @param \Closure|null $closure
     *
     * @return \Generator|void
     * @throws \Database\Exceptions\BuilderException
     */
    public function each(\Closure $closure = null)
    {
        $this->buildSelect();
        $this->run();

        while ($row = $this->stmt->fetch($this->fetchStyle)) {
            if(is_null($closure)) {
                yield $row;
            }
            else {
                $closure($row);
            }
        }
    }

    /**
     * @return int
     * @throws \Database\Exceptions\BuilderException
     */
    public function count()
    {
        $this->parts['select'] = [];
        $this->parts['selectRaw'] = ["COUNT(*)"];
        $this->buildSelect();
        $this->run();

        return (int) $this->stmt->fetchColumn();
    }

    /**
     * @param array $attributes
     *
     * @return bool|null
     * @throws \Database\Exceptions\BuilderException
     */
    public function create(array $attributes)
    {
        $this->buildInsert($attributes);

        return $this->run();
    }

    /**
     * @param array $items
     *
     * @return bool|null
     * @throws \Database\Exceptions\BuilderException
     */
    public function update(array $items) {
        $columns = self::testArrayOfStrings(array_keys($items));
        $values = array_values($items);

        $updates = array_map(fn($column) => "{$column} = ?", $columns);
        $this->parts['update'] = $updates;

        //reverse merge due to query layout and update being called for execution
        $this->parts['bindings'] =  array_merge($values, $this->parts['bindings']);

        $this->buildUpdate();

        return $this->run();
    }

    protected function buildUpdate() {
        $conditions = trim(self::buildWhereConditions($this->parts['where']));
        $where = ! empty($conditions) ? "WHERE {$conditions}" : '';
        $itemsToSet = self::buildCondition('%s', ', ', $this->parts['update']);

        $this->query = sprintf(
            "UPDATE %s SET %s %s",
            $this->table,
            $itemsToSet,
            $where
        );
    }

    /**
     * @return bool|null
     * @throws \Database\Exceptions\BuilderException
     */
    protected function run()
    {
        $result = null;

        $this->setConnection();

        if (! $this->stmt = $this->connection->prepare($this->query)) {
            $this->handleError();
        }

        if (! $result = $this->stmt->execute($this->parts['bindings'])) {
            $this->handleError();
        }

        return $result;
    }


    /**
     * @param $query
     *
     * @return mixed
     * @throws \Database\Exceptions\BuilderException
     */
    public function raw($query)
    {
        $this->setConnection();

        return $this->connection->exec($query);
    }

    /**
     * @throws BuilderException
     */
    private function handleError()
    {
        throw new BuilderException(implode(' ', array_values($this->connection->errorInfo())));
    }

    /**
     * @throws \Database\Exceptions\BuilderException
     */
    private function setConnection()
    {
        if (empty($this->driver)) {
            throw new BuilderException('Database driver not set');
        }

        $this->connection = $this->driver::getConnection();
    }

}