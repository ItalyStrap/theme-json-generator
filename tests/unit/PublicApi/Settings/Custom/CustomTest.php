<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Custom;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;

final class CustomTest extends UnitTestCase
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
}
