<?php

namespace Pitech\FiltersBundle\Filters\TypeGuesser;

use Doctrine\ORM\EntityManagerInterface;
use Pitech\FiltersBundle\Annotations\FilterFieldReader;
use Pitech\FiltersBundle\Filters\FilterInterface;
use Pitech\FiltersBundle\Filters\FilterOptions;

class FilterTypeGuesser
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var FilterFieldReader
     */
    protected $filterFieldReader;

    /**
     * @param EntityManagerInterface $em
     * @param FilterFieldReader      $filterFieldReader
     */
    public function __construct(
        EntityManagerInterface $em,
        FilterFieldReader $filterFieldReader
    ) {
        $this->em = $em;
        $this->filterFieldReader = $filterFieldReader;
    }

    /**
     * Determines the type of which filter class to instantiate, based on
     * the structured array received and the given entity class.
     *
     * @param FilterOptions $filterOptions
     * @param string        $entity
     *
     * @return null|FilterInterface
     */
    public function guessFilterTypeFromArray($filterOptions, $entity)
    {
        $filterType = null;
        /* in case the attribute to filter by is situated in another entity,
        parse the connected entities until you reach its entity */
        if ($path = $filterOptions->getPathArray()) {
            while ($path) {
                $entity = $this
                    ->em
                    ->getClassMetadata($entity)
                    ->getAssociationMapping(array_shift($path))
                ['targetEntity'];
            }
        }

        if ($filter = $this->filterFieldReader->getFilter(
            $entity,
            $attribute = $filterOptions->getName()
        )
        ) {
            /* get the type of the attribute that corresponds to the filter */

            $attributeType = isset($filter[$attribute]['options']['type']) ?
                $filter[$attribute]['options']['type'] :
                $this
                    ->em
                    ->getClassMetadata($entity)
                    ->getTypeOfField($filter[$attribute]['property']);

            $filterOptions->setAttribute($filter[$attribute]['property']);
            if (sizeof($filterOptions->getValues()) == 1) {
                switch ($attributeType) {
                    case 'integer':
                        $filterType = FilterInterface::NUMERIC_FILTER;
                        break;
                    case 'text':
                    case 'string':
                        $filterType = FilterInterface::STRING_FILTER;
                        break;
                    case 'date':
                        $filterType = FilterInterface::DATE_FILTER;
                        break;
                    case 'checker':
                        $filterType = FilterInterface::CHECK_FILTER;
                        break;
                }
            } else {
                switch ($attributeType) {
                    case 'integer':
                        $filterType = FilterInterface::NUMERIC_INTERVAL_FILTER;
                        break;
                    case 'date':
                        $filterType = FilterInterface::DATE_INTERVAL_FILTER;
                        break;
                    case 'string':
                        $filterType = FilterInterface::OR_STRING_FILTER;
                        break;
                    case 'date_check':
                        $filterType = FilterInterface::DATE_CHECK_FILTER;
                        break;
                }
            }
        }

        return $filterType;
    }
}
