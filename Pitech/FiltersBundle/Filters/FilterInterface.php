<?php

namespace Pitech\FiltersBundle\Filters;

use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    const NUMERIC_FILTER = 'numeric_filter';
    const NUMERIC_INTERVAL_FILTER = 'numeric_interval_filter';
    const STRING_FILTER = 'string_filter';
    const OR_STRING_FILTER = 'or_string_filter';
    const DATE_FILTER = 'date_filter';
    const DATE_INTERVAL_FILTER = 'date_interval_filter';
    const DATE_CHECK_FILTER = 'date_check_filter';
    const CHECK_FILTER = 'check_filter';

    /**
     * Set the entity attribute on which the filter should be applied.
     *
     * @param string $attribute
     */
    public function setAttribute($attribute);

    /**
     * Returns the entity attribute corresponding to the filter.
     *
     * @return string
     */
    public function getAttribute();

    /**
     * Set the filters' expression.
     *
     * @param string $expression
     */
    public function setExpression($expression);

    /**
     * Get the filters' expression.
     *
     * @return string
     */
    public function getExpression();

    /**
     * Set the value to filter by.
     *
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * Get the value to filter by.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Add a filter option given as string.
     *
     * @param string $option
     */
    public function addOption($option);

    /**
     * Return true if a filter has the option given as string, null otherwise.
     *
     * @param string $option
     *
     * @return bool
     */
    public function hasOption($option);

    /**
     * Retrieve the filter options given as array.
     *
     * @return array $options
     */
    public function getOptions();

    /**
     * Adds a DQL clause to the received query builder object with given
     * parameters, and returns the query builder object.
     *
     * @param QueryBuilder $qb
     * @param string       $alias
     *
     * @return QueryBuilder
     */
    public function getDQLExpression($qb, $alias);

    /**
     * Returns filter type.
     *
     * @return string
     */
    public function getType();
}
