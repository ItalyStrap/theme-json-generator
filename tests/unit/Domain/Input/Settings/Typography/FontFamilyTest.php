<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Typography;

use ItalyStrap\Tests\Unit\Domain\Input\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontFamily;

class FontFamilyTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'base';

    private string $name = 'Font Family';

    private string $fontFamily = 'The font family';

    private function makeInstance(): FontFamily
    {
        return new FontFamily(
            $this->slug,
            $this->name,
            $this->fontFamily
        );
    }
}
