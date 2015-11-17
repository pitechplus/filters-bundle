<?php

namespace Pitech\FiltersBundle\Filters;

class DateIntervalFilter extends AbstractFilter implements FilterInterface
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
        if (sizeof($this->entitiesPathArray) > 0) {
            $qb = $this->performJoins($qb, $alias);
        }
        if (in_array($this->expression, static::$expressionArray)) {
            $qb->andWhere(
                $qb->expr()->between(
                    sprintf('%s.%s', $alias, $this->attribute),
                    $qb->expr()->literal($this->value[0]),
                    $qb->expr()->literal($this->value[1])
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
        return self::DATE_INTERVAL_FILTER;
    }
}
