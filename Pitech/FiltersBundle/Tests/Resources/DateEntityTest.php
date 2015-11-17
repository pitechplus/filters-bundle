<?php

namespace Pitech\FiltersBundle\Tests\Resources;

use Doctrine\ORM\Mapping as ORM;
use Pitech\FiltersBundle\Annotations\FilterField;

class DateEntityTest
{
    /**
     * @var date
     *
     * @FilterField(options={format="yyyy-mm-dd"})
     *
     * @ORM\Column(type="date", nullable=false)
     */
    protected $date;
}
