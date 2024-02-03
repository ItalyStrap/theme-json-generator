<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Custom;

use ItalyStrap\Tests\Unit\Domain\Input\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Custom\Custom;

class CustomTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'base';

    private string $name = 'Custom';

    private function makeInstance(): Custom
    {
        return new Custom(
            $this->slug,
            $this->name
        );
    }

    public function testSlug()
    {
    }

    public function testProp()
    {
    }
}
