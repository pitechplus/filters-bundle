<?php

namespace Pitech\FiltersBundle\Filters;

class OrStringFilter extends AbstractFilter implements FilterInterface
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
            $orX = $qb->expr()->orX();
            foreach ($this->value as $value) {
                $orX->add(
                    call_user_func_array(
                        [$qb->expr(), $this->expression],
                        [
                            sprintf('%s.%s', $alias, $this->attribute),
                            $qb->expr()->literal('%'.$value.'%'),
                        ]
                    )
                );
            }
            $qb->andWhere($orX);
        } elseif (is_null($this->expression)) {
            $orX = $qb->expr()->orX();
            foreach ($this->value as $value) {
                $orX->add(
                    $qb->expr()->eq(
                        sprintf('%s.%s', $alias, $this->attribute),
                        $qb->expr()->literal($value)
                    )
                );
            }
            $qb->andWhere($orX);
        }
        return $qb;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::OR_STRING_FILTER;
    }
}
