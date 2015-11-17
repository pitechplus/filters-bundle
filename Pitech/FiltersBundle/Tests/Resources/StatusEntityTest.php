<?php

namespace Pitech\FiltersBundle\Tests\Resources;

use Pitech\FiltersBundle\Annotations\FilterField;

class StatusEntityTest
{
    /**
     * @var integer
     * @FilterField
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $status;
}
