<?php

namespace Pitech\FiltersBundle\Filters;

/**
 *  DateCheckFilters verifies if the specific field contains the date
 *  with the specific expression or if the date field is NULL
 */
class DateCheckFilter extends AbstractFilter implements FilterInterface
{
    private static $expressionArray = ['gt', 'lt', 'gte', 'lte'];

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
            $orX = $qb->expr()->orX();
            $orX->add(
                call_user_func_array(
                    [$qb->expr(), $this->expression],
                    [
                        sprintf('%s.%s', $alias, $this->attribute),
                        $qb->expr()->literal($this->value[0]),
                    ]
                )
            );
            $orX->add(
                call_user_func_array(
                    [$qb->expr(), 'is'.$this->value[1]],
                    [sprintf('%s.%s', $alias, $this->attribute)]
                )
            );
            $qb->andWhere($orX);
        }
        return $qb;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::DATE_CHECK_FILTER;
    }
}
