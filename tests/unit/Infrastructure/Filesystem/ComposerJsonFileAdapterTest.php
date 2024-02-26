<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Infrastructure\Filesystem;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ComposerJsonFileAdapter;
use Prophecy\Argument;

class ComposerJsonFileAdapterTest extends UnitTestCase
{
    protected function makeInstance(): ComposerJsonFileAdapter
    {
        return new ComposerJsonFileAdapter($this->makeJsonFile());
    }

    public function testItShouldBeInstantiatable(): void
    {
        $sut = $this->makeInstance();
    }

    /**
     * @throws \Exception
     */
    public function testItShouldWrite(): void
    {
        $sut = $this->makeInstance();
        $sut->write($this->inputData());
        $this->jsonFile->write(Argument::type('array'), Argument::any())->shouldHaveBeenCalled();
    }
}
