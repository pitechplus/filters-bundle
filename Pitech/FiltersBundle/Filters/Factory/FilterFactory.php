<?php

namespace Pitech\FiltersBundle\Filters\Factory;

use Pitech\FiltersBundle\Filters\DateFilter;
use Pitech\FiltersBundle\Filters\DateIntervalFilter;
use Pitech\FiltersBundle\Filters\NumericFilter;
use Pitech\FiltersBundle\Filters\NumericIntervalFilter;
use Pitech\FiltersBundle\Filters\StringFilter;
use Pitech\FiltersBundle\Filters\TypeGuesser\FilterTypeGuesser;
use Pitech\FiltersBundle\Filters\Helper\FilterHelper;
use Pitech\FiltersBundle\Filters\Service\FilterTypeService;
use Pitech\FiltersBundle\Filters\FilterInterface;
use Pitech\FiltersBundle\Filters\FilterOptions;

class FilterFactory
{
    /**
     * @var FilterHelper
     */
    protected $filterHelper;

    /**
     * @var FilterTypeGuesser
     */
    protected $filterTypeGuesser;

    /**
     * @var string
     */
    protected $filtersDelimiter;

    /**
     * @var string
     */
    protected $filtersOrDelimiter;

    /**
     * @var string
     */
    protected $expressionDelimiter;

    /**
     * @var string
     */
    protected $intervalDelimiter;

    /**
     * @var FilterTypeService
     */
    protected $filterTypeService;

    /**
     * Class constructor.
     *
     * @param FilterHelper $filterHelper
     * @param FilterTypeGuesser $filterTypeGuesser
     * @param string $filtersDelimiter
     * @param string $expressionDelimiter
     * @param string $intervalDelimiter
     */
    public function __construct(
        FilterHelper $filterHelper,
        FilterTypeGuesser $filterTypeGuesser,
        FilterTypeService $filterTypeService,
        $filtersDelimiter,
        $filtersOrDelimiter,
        $expressionDelimiter,
        $intervalDelimiter
    ) {
        $this->filterHelper = $filterHelper;
        $this->filterTypeGuesser = $filterTypeGuesser;
        $this->filtersDelimiter = $filtersDelimiter;
        $this->filtersOrDelimiter = $filtersOrDelimiter;
        $this->expressionDelimiter = $expressionDelimiter;
        $this->intervalDelimiter = $intervalDelimiter;
        $this->filterTypeService = $filterTypeService;
    }

    /**
     * Receives a string of query parameters and returns a nested array
     * with filter components: attribute, value, expression(optional).
     *
     * @param string $filtersString
     *
     * @return array $filters
     */
    public function transform($filtersString)
    {
        if ($this->filterHelper->checkFormat($filtersString)) {
            return $this
                ->filterHelper
                ->stripFilterQueryToFilterOptions(
                    $filtersString,
                    $this->filtersDelimiter,
                    $this->filtersOrDelimiter,
                    $this->expressionDelimiter,
                    $this->intervalDelimiter
                );
        }
    }

    /**
     * Return a collection of filter objects from the query string given.
     *
     * @param string $filtersString
     * @param string $entityClass
     *
     * @return array
     */
    public function getFilters($filtersString, $entityClass)
    {
        /* get an array of FilterOptions objects from the filters string */
        $filtersOptions = $this->transform($filtersString);
        /* get the filter classes from the filter factory from the filters array */
        $filters = [];

        if ($filtersOptions) {
            foreach ($filtersOptions as $filterOptions) {
                $filters[] = $this->getFilter($filterOptions, $entityClass);
            }
        }

        return $filters;
    }

    /**
     * Return a filter object from a structured array.
     *
     * @param FilterOptions $filterOptions
     * @param string $entityClass
     *
     * @return FilterInterface
     */
    public function getFilter($filterOptions, $entityClass)
    {
        /* evaluate nested array to determine the type of filter to be returned */
        $filter = null;
        if ($filterOptions) {
            $filterType = $this
                ->filterTypeGuesser
                ->guessFilterTypeFromArray($filterOptions, $entityClass);

            $filter = $this->filterTypeService->getFilterClassByType($filterType);
            $filter = $filter ?
                new $filter(
                    $filterOptions->getAttribute(),
                    $filterOptions->getExpression(),
                    $filterOptions->getValues(),
                    $filterOptions->getPathArray()) :
            null;
        }

        return $filter;
    }
}
