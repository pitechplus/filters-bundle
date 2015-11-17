<?php

namespace Pitech\FiltersBundle\Filters;

class StringFilter extends AbstractFilter implements FilterInterface
{
    private static $expressionArray = ['like'];

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
                    [$qb->expr(), $this->expression],
                    [
                        sprintf('%s.%s', $alias, $this->attribute),
                        $qb->expr()->literal('%'.$this->value.'%'),
                    ]
                )
            );
        } elseif (is_null($this->expression)) {
            $qb->andWhere(
                $qb->expr()->eq(
                    sprintf('%s.%s', $alias, $this->attribute),
                    $qb->expr()->literal($this->value)
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
        return self::STRING_FILTER;
    }
}
