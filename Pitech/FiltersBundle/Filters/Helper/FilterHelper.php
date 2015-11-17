<?php

namespace Pitech\FiltersBundle\Filters\Helper;

use Pitech\FiltersBundle\Filters\FilterOptions;
use Psr\Log\LoggerInterface;

class FilterHelper
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Check if string of filters is of correct format.
     *
     * @param string $filtersString
     *
     * @return bool
     */
    public function checkFormat($filtersString)
    {
        return
            strpos($filtersString, '[') == 0 &&
            strpos($filtersString, ']') == strlen($filtersString) - 1;
    }

    /**
     * Transforms the filters' string into an array of FilterOptions objects.
     *
     * @param string $filtersString
     * @param string $filtersDelimiter
     * @param string $expressionDelimiter
     * @param string $intervalDelimiter
     *
     * @return array $filtersOptions
     *
     * @throws \InvalidArgumentException
     */
    public function stripFilterQueryToFilterOptions(
        $filtersString,
        $filtersDelimiter,
        $filtersOrDelimiter,
        $expressionDelimiter,
        $intervalDelimiter
    ) {
        /* explode the filters string received into an array of individual strings
        representing a single filter */
        $filtersStringArray = explode($filtersDelimiter, substr($filtersString, 1, -1));
        foreach ($filtersStringArray as $key => $filterString) {
            if (sizeof(explode('=', $filterString)) > 1) {
                $filterOptions = new FilterOptions();
                $filterAttributeValue[] = explode('=', $filterString);

                /* we have to deal with an expression if $exprWithValues has
                   more than 1 element, separated by the $expressionDelimiter
                   so we set the expression and the values to the $filterOptions object */
                if (sizeof($exprWithValues = explode($expressionDelimiter, $filterAttributeValue[$key][1])) > 1) {
                    $filterOptions->setExpression(array_shift($exprWithValues));

                    /* if the OR delimiter is found in the $exprWithValues string,
                       explode by the said delimiter */
                    if (strpos($exprWithValues[0], $filtersOrDelimiter)) {
                        $exprWithValues = explode($filtersOrDelimiter, $exprWithValues[0]);
                    }

                    /* if the interval delimiter is found in the $exprWithValues string,
                       explode by the said delimiter */
                    if (strpos($exprWithValues[0], $intervalDelimiter)) {
                        $exprWithValues = explode($intervalDelimiter, $exprWithValues[0]);
                    }
                    $filterOptions->setValues($exprWithValues);

                /* we have to deal with the default expression so we set only the
                 * value to the $filterOptions
                 */
                } else {

                    /* if the OR delimiter is found in the filterValues string,
                     * explode by the said delimiter
                     */
                    if (strpos($filterAttributeValue[$key][1], $filtersOrDelimiter)) {
                        $filterOrValues = explode($filtersOrDelimiter, $filterAttributeValue[$key][1]);
                    }
                    $filterOptions->setValues(isset($filterOrValues) ? $filterOrValues : $filterAttributeValue[$key][1]);
                }

                /* explode string before '=' to determine if the filter is in related entity */
                $filterPath = explode('.', $filterAttributeValue[$key][0]);
                $filterOptions->setName(array_pop($filterPath));
                if (sizeof($filterPath) > 0) {
                    $filterOptions->setPathArray($filterPath);
                }
                $filtersOptions[] = $filterOptions;
            } else {
                /* TODO: refactor this when implementing logs all over the project */
                $this->logger->error(
                    'Invalid arguments in filter usage.',
                    array(
                        'path' => '\Pitech\FiltersBundle\Helper\FilterHelper',
                        'function' => 'stripFilterQueryToFilterOptions',
                        'filter_string' => $filtersString,
                    )
                );
                throw new \InvalidArgumentException();
            }
        }

        return $filtersOptions;
    }
}
