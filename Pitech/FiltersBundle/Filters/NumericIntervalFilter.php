<?php

namespace Pitech\FiltersBundle\Filters;

class NumericIntervalFilter extends AbstractFilter implements FilterInterface
{
    private static $expressionArray = ['bw'];

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string                     $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getDQLExpression($qb, $alias)
    {
        if ($this->entitiesPathArray) {
            $qb = $this->performJoins($qb, $alias);
        }
        if (in_array($this->expression, static::$expressionArray) &&
            $this->value[0] && $this->value[1]
        ) {
            $qb->andWhere(
                $qb->expr()->between(
                    sprintf('%s.%s', $alias, $this->attribute),
                    floatval($this->value[0]),
                    floatval($this->value[1])
                )
            );
        }

        return $qb;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::NUMERIC_INTERVAL_FILTER;
    }
}
