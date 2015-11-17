<?php

namespace Pitech\FiltersBundle\Filters;

abstract class AbstractFilter
{
    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var string
     */
    protected $expression;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Variable containing array of related entities on which the filter
     * should be applied.
     *
     * @var mixed
     */
    protected $entitiesPathArray;

    /**
     * An array of options related to the filter.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Class constructor.
     *
     * @param string $attribute
     * @param mixed  $expression
     * @param mixed  $value
     * @param mixed  $entitiesPathArray
     */
    public function __construct($attribute, $expression, $value, $entitiesPathArray)
    {
        $this->attribute = $attribute;
        $this->value = $value;
        $this->expression = $expression;
        $this->entitiesPathArray = $entitiesPathArray;
    }

    /**
     * @param string $attribute
     *
     * @return $this
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @param string $expression
     *
     * @return $this
     */
    public function setExpression($expression)
    {
        $this->expression = strtolower($expression);

        return $this;
    }

    /**
     * @return string
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Add a filter option given as string.
     *
     * @param string $option
     *
     * @return object
     */
    public function addOption($option)
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * @param array $entitiesPathArray
     *
     * @return $this
     */
    public function setEntitiesPathArray($entitiesPathArray)
    {
        $this->entitiesPathArray = $entitiesPathArray;

        return $this;
    }

    /**
     * @return array
     */
    public function getEntitiesPathArray()
    {
        return $this->entitiesPathArray;
    }

    /**
     * Return true if a filter has the option given as string, null otherwise.
     *
     * @param string $option
     *
     * @return bool
     */
    public function hasOption($option)
    {
        return in_array($option, $this->options);
    }

    /**
     * Retrieve the filter options given as array.
     *
     * @return array $options
     */
    public function getOptions()
    {
        return $this->options;
    }

    protected function performJoins($qb, &$alias)
    {
        $entitiesArray = $this->entitiesPathArray;
        while (sizeof($entitiesArray) > 0) {
            $entity = array_shift($entitiesArray);
            if (!in_array(strtolower($entity), $qb->getAllAliases())) {
                $qb->innerJoin(
                    sprintf('%s.%s', $alias, $entity),
                    $alias = strtolower($entity)
                );
            }
            $alias = strtolower($entity);
        }

        return $qb;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string                     $alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    abstract public function getDQLExpression($qb, $alias);

    /**
     * @return string
     */
    abstract public function getType();
}
