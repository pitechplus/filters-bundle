<?php

namespace Pitech\FiltersBundle\Filters\Service;

use Pitech\FiltersBundle\Filters\FilterInterface;

class FilterTypeService
{
    private $filterTypeMappings = [
        FilterInterface::NUMERIC_FILTER => 'Pitech\FiltersBundle\Filters\NumericFilter',
        FilterInterface::NUMERIC_INTERVAL_FILTER => 'Pitech\FiltersBundle\Filters\NumericIntervalFilter',
        FilterInterface::STRING_FILTER => 'Pitech\FiltersBundle\Filters\StringFilter',
        FilterInterface::OR_STRING_FILTER => 'Pitech\FiltersBundle\Filters\OrStringFilter',
        FilterInterface::DATE_FILTER => 'Pitech\FiltersBundle\Filters\DateFilter',
        FilterInterface::DATE_INTERVAL_FILTER => 'Pitech\FiltersBundle\Filters\DateIntervalFilter',
        FilterInterface::CHECK_FILTER => 'Pitech\FiltersBundle\Filters\CheckFilter',
        FilterInterface::DATE_CHECK_FILTER => 'Pitech\FiltersBundle\Filters\DateCheckFilter',
    ];

    public function getFilterClassByType($filterType)
    {
        return isset($this->filterTypeMappings[$filterType]) ?
            $this->filterTypeMappings[$filterType] :
            null;
    }
}
