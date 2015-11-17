<?php

namespace Pitech\FiltersBundle\Tests\Resources;

use Pitech\FiltersBundle\Annotations\FilterField;

class FilterAnnotationTest
{
    /**
     * @var string testField
     *
     * @FilterField(name="test", options{"testOptions" : "testValue"}))
     *
     */
    protected $testField;
}
