<?php

namespace Pitech\FiltersBundle\Annotations;

/**
 *  FilterField annotations reader class
 */
class FilterFieldReader
{
    private $filtersCache;

    private $dir;

    private $loadedAnnotations = array();

    public function __construct($filtersCache, $dir)
    {
        $this->filtersCache = $filtersCache;
        $this->dir = $dir;
    }

    /**
     * Returns the FilterField annotation values
     *
     * @param Entity $entityClass [Class name which contains FilterField annotation]
     * @param string $key         [Annotation name property]
     *
     * @return array
     */
    public function getFilter($entityClass, $key)
    {
        if (isset($this->loadedAnnotations[$key])) {
            return $this->loadedAnnotations[$key];
        }

        $path = $this->dir.'/'.$entityClass.'.cache.php';
        if (!is_file($path)) {
            $annotations = $this->filtersCache->getPropertyFilterAnnotations($entityClass);
            if ($annotations) {
                $this->filtersCache->saveCacheFile($path, $annotations);
                return $annotations[$key];
            }
            return;
        }
        $this->loadedAnnotations = include $path;
        return isset(
            $this->loadedAnnotations[$key]) ?
            $this->loadedAnnotations[$key] :
            null;
    }
}
