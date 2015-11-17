<?php

namespace Pitech\FiltersBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class FilterField extends Annotation
{
    public $name;

    public $options = array();

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
