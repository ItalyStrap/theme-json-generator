<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Input\Settings\Typography;

use ItalyStrap\Tests\Unit\Domain\Input\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\Typography\FontSize;

class FontSizeTest extends UnitTestCase
{
    use PresetCommonTrait;

    private string $slug = 'base';

    private string $name = 'Font Size';

    private string $size = '16px';

    private function makeInstance(): FontSize
    {
        return new FontSize(
            $this->slug,
            $this->name,
            $this->size,
            null
        );
    }
}
