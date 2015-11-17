<?php

namespace Pitech\FiltersBundle\Tests\Annotations;

use Prophecy\PhpUnit\ProphecyTestCase as BaseTest;
use Pitech\FiltersBundle\Annotations\FilterFieldReader;

class FilterFieldReaderTest extends BaseTest
{

    private $filtersCache;
    private $dir;

    private $class;

    protected function setUp()
    {
        parent::setUp();

        $this->filtersCache = $this
            ->prophesize('Pitech\FiltersBundle\Annotations\FiltersCache');
        $this->dir = __DIR__ . '/../Resources/public';
        $this->class = new FilterFieldReader($this->filtersCache->reveal(), $this->dir);
    }

    /**
     * @dataProvider getDataProviderForTestGetFilter
     * 
     * @param string $class
     * @param string $key
     * @param array  $expected
     */
    public function testGetFilter($class, $key, $expected)
    {
        $this
            ->filtersCache
            ->getPropertyFilterAnnotations($class)
            ->shouldBeCalledTimes(1)
            ->willReturn(null);
        $response = $this
            ->class
            ->getFilter($class, $key);

        $this->assertEquals($expected, $response);
    }

    /**
     * @dataProvider getDataProviderForTestGetFilter2
     * 
     * @param string $class
     * @param string $key
     * @param array  $expected
     * @param string $path
     */
    public function testGetFilter2($class, $key, $expected, $path)
    {
        $this
            ->filtersCache
            ->getPropertyFilterAnnotations($class)
            ->shouldBeCalledTimes(1)
            ->willReturn($expected);
        $this
            ->filtersCache
            ->saveCacheFile($path, $expected)
            ->shouldBeCalledTimes(1)
            ->willReturn(null);
        $response = $this
            ->class
            ->getFilter($class, $key);

        $this->assertEquals($expected[$key], $response);
    }

    public function getDataProviderForTestGetFilter()
    {
        return array(
            array(
                "Pitech\FiltersBundle\Tests\Resources\StatusEntityTest",
                'status',
                null,
            ),
        );
    }

    public function getDataProviderForTestGetFilter2()
    {
        $expected = array(
            'test' => array(
                'test' => array(
                        'property' => 'testField',
                        'options' => array(
                            'testOptions' => "testValue",
                        ),
                    ),
                ),
            );
        $expected2 = array(
            'date' => array(
                    'property' => 'date',
                    'options' => array(
                        'format' => "yyyy-mm-dd",
                    ),
                ),
            );
        return array(
            array(
                "Pitech\FiltersBundle\Tests\Resources\DateEntityTest",
                'date',
                $expected2,
                __DIR__ . '/../Resources/public/Pitech\FiltersBundle\Tests\Resources\DateEntityTest.cache.php',
            ),

            array(
                "Pitech\FiltersBundle\Tests\Resources\FilterAnnotationTest",
                'test',
                $expected,
                __DIR__ . '/../Resources/public/Pitech\FiltersBundle\Tests\Resources\FilterAnnotationTest.cache.php',
            ),
        );
    }
}
