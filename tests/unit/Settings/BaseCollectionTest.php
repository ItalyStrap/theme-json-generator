<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\CollectionInterface;

abstract class BaseCollectionTest extends UnitTestCase
{
    /**
     * @var \string[][]
     */
    protected $collection;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $key = '';

    abstract protected function makeInstance(): CollectionInterface;

    abstract public function valueProvider();

    /**
     * @test
     */
    public function itShouldReturnTheCollection()
    {
        $sut = $this->makeInstance();
        $collection = $sut->toArray();

        $this->assertEquals($this->collection, $collection, '');
    }

    /**
     * @test
     */
    public function itShouldThrownExceptionIfValueDoesNotExist()
    {
        $sut = $this->makeInstance();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Value of secondary does not exists.');
        $val = $sut->value('secondary');
    }

    /**
     * @test
     */
    public function itShouldThrownExceptionIfPropDoesNotExist()
    {
        $sut = $this->makeInstance();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('secondary does not exists.');
        $prop = $sut->propOf('secondary');
    }
}
