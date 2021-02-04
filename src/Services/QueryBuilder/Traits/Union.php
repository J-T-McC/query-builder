<?php

namespace Database\Services\QueryBuilder\Traits;

use Database\Exceptions\InvalidArgumentException;

trait Union
{

    /**
     * @param \Database\Services\QueryBuilder\Traits\Union $builder
     *
     * @return $this
     */
    public function union(self $builder)
    {
        $this->parts['unions'][] = $builder;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryForUnion()
    {
        $this->buildSelect();

        return $this->query;
    }


    /**
     * @throws \Database\Exceptions\InvalidArgumentException
     */
    private function applyUnions()
    {
        foreach ($this->parts['unions'] as $union) {
            if($union === $this) {
                throw new InvalidArgumentException(
                    'Encountered attempt to union self to query. Must use unique Builder instances for unions.'
                );
            }
            $this->query .= sprintf(' UNION %s', $union->getQueryForUnion());
            $this->parts['bindings'] = array_merge($this->parts['bindings'], $union->getBindings());
        }
    }

}