<?php

namespace Pitech\FiltersBundle\Annotations;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Util\Inflector;

class FiltersCache
{
    /**
     * @var string
     */
    private $dir;

     /**
     * Constructor.
     *
     * @param string  $cacheDir
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($cacheDir)
    {
        if (!is_dir($cacheDir) && !@mkdir($cacheDir, 0777, true)) {
            throw new \InvalidArgumentException(
                sprintf('The directory "%s" does not exist and could not be created.', $cacheDir)
            );
        }

        $this->dir = rtrim($cacheDir, '\\/');
    }

    /**
     * Retrives the annotation options
     *
     * @param Entity $entityClass
     *
     * @return array
     */
    public function getPropertyFilterAnnotations($entityClass)
    {
        $reader = new AnnotationReader();
        $reflectionObj = new \ReflectionClass($entityClass);
        $annotations = array();

        $classProperties = $reflectionObj->getProperties();
        foreach ($classProperties as $property) {
            $reflectionProp = new \ReflectionProperty($entityClass, $property->getName());
            $relation = $reader->getPropertyAnnotation($reflectionProp, 'Pitech\\FiltersBundle\\Annotations\\FilterField');
            if ($relation) {
                if (!$relation->getName()) {
                    $relation->setName($property->getName());
                }
                $annotations[$relation->getName()] = $this->getTransformedAnnotation($relation, $property->getName());
            }
        }
        return $annotations;
    }

    /**
     * Retrive the property with options for asked name
     *
     * @param \FilterField $relation
     * @param string       $property
     *
     * @return array
     */
    private function getTransformedAnnotation($relation, $property)
    {
        $info = array();
        $info[$relation->getName()] = array(
            'property' => $property,
            'options' => $relation->getOptions(),
        );

        return $info;
    }

    /**
     * Saves the cache file.
     *
     * @param string $path
     * @param mixed  $data
     *
     * @return void
     */
    public function saveCacheFile($path, $data)
    {
        if (!is_writable($this->dir)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The directory "%s" is not writable.
                    Both, the webserver and the console user need access.
                    You can manage access rights for multiple users with "chmod +a".
                    If your system does not support this, check out the acl package.',
                    $this->dir
                )
            );
        }

        $tempfile = tempnam($this->dir, uniqid('', true));

        if (false === $tempfile) {
            throw new \RuntimeException(sprintf('Unable to create tempfile in directory: %s', $this->dir));
        }

        $written = file_put_contents($tempfile, '<?php return unserialize('.var_export(serialize($data), true).');');

        if (false === $written) {
            throw new \RuntimeException(sprintf('Unable to write cached file to: %s', $tempfile));
        }

        if (false === rename($tempfile, $path)) {
            @unlink($tempfile);
            throw new \RuntimeException(sprintf('Unable to rename %s to %s', $tempfile, $path));
        }

        @chmod($path, 0666 & ~umask());
    }
}
