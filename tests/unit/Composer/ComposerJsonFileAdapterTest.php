<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Composer;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Composer\ComposerJsonFileAdapter;
use Prophecy\Argument;

class ComposerJsonFileAdapterTest extends UnitTestCase
{
    protected function makeInstance(): ComposerJsonFileAdapter
    {
        return new ComposerJsonFileAdapter($this->makeJsonFile());
    }

    /**
     * @test
     */
    public function itShouldBeInstantiatable()
    {
        $sut = $this->makeInstance();
    }

    /**
     * @test
     * @throws \Exception
     */
    public function itShouldWrite()
    {
        $sut = $this->makeInstance();
        $sut->write($this->inputData());
        $this->jsonFile->write(Argument::type('array'), Argument::any())->shouldHaveBeenCalled();
    }
}
