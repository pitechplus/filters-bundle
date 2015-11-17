<?php

namespace Pitech\FiltersBundle\Filters;

class CheckFilter extends AbstractFilter implements FilterInterface
{
    private static $expressionArray = ['is'];

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
        if (in_array($this->expression, static::$expressionArray)) {
            $qb->andWhere(
                call_user_func_array(
                    [$qb->expr(), $this->expression.$this->value],
                    [sprintf('%s.%s', $alias, $this->attribute)]
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
        return self::CHECK_FILTER;
    }
}
