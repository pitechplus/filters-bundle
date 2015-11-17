<?php

namespace Pitech\FiltersBundle\Filters\Service;

use Doctrine\ORM\QueryBuilder;
use Pitech\FiltersBundle\Filters\Factory\FilterFactory;

class FilterService
{
    /**
     * @var \Pitech\FiltersBundle\Filters\Factory\FilterFactory
     */
    protected $filterFactory;

    /**
     * @var string
     */
    protected $bundleEntitiesFolder;

    /**
     * Class constructor.
     *
     * @param FilterFactory $filterFactory
     * @param string        $bundleEntitiesFolder
     */
    public function __construct(FilterFactory $filterFactory, $bundleEntitiesFolder)
    {
        $this->filterFactory = $filterFactory;
        $this->bundleEntitiesFolder = $bundleEntitiesFolder;
    }

    /**
     * Adds filter DQL queries to a received QueryBuilder object and
     * a query string containing filter expression.
     *
     * @param string       $filtersString
     * @param QueryBuilder $qb
     * @param string       $entityClass
     * @param string       $alias
     *
     * @return QueryBuilder
     */
    public function filter($filtersString, QueryBuilder $qb, $entityClass, $alias)
    {
        $filters = $this
            ->filterFactory
            ->getFilters(
                $filtersString,
                sprintf('%s%s', $this->bundleEntitiesFolder, $entityClass)
            );

        /* foreach $filter in $filters apply $filter->getDQLExpression to $qb
        to actually apply filtering */
        if ($filters) {
            foreach ($filters as $filter) {
                if ($filter) {
                    $qb = $filter->getDQLExpression($qb, $alias);
                }
            }
        }

        return $qb;
    }
}
