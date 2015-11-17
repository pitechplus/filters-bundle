<?php

namespace Pitech\FiltersBundle\Filters;

class FilterOptions
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var string
     */
    protected $expression;

    /**
     * @var array
     */
    protected $values;

    /**
     * Array of entities used to build the path to the filters' attribute.
     *
     * @var array
     */
    protected $pathArray;

    /**
     * @param string $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
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
        $this->expression = $expression;

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
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $pathArray
     *
     * @return $this
     */
    public function setPathArray($pathArray)
    {
        $this->pathArray = $pathArray;

        return $this;
    }

    /**
     * @return array
     */
    public function getPathArray()
    {
        return $this->pathArray;
    }

    /**
     * @param array $values
     *
     * @return $this
     */
    public function setValues($values)
    {
        if ($this->expression && sizeof($values) == 1) {
            $this->values = $values[0];
        } else {
            $this->values = $values;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
