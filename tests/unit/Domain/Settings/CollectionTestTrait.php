<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings;

trait CollectionTestTrait
{
    protected array $collection;

    protected string $category;

    protected string $key = '';

    abstract protected function makeInstance(): object;

    abstract public function valueProvider();

    public function testItShouldReturnTheCollection()
    {
        $sut = $this->makeInstance();
        $collection = $sut->toArray();

        $this->assertEquals($this->collection, $collection, '');
    }

    public function testItShouldThrownExceptionIfValueDoesNotExist()
    {
        $sut = $this->makeInstance();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Value of secondary does not exists.');
        $val = $sut->value('secondary');
    }

    public function testItShouldThrownExceptionIfPropDoesNotExist()
    {
        $sut = $this->makeInstance();
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('secondary does not exists.');
        $prop = $sut->propOf('secondary');
    }
}
