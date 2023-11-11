<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings;

use ItalyStrap\Tests\UnitTestCase;

abstract class BaseCollectionTest extends UnitTestCase
{
    /**
     * @var \string[][]
     */
    protected array $collection;

    /**
     * @var string
     */
    protected string $category;

    /**
     * @var string
     */
    protected $key = '';

    abstract protected function makeInstance(): object;

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
