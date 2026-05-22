<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings\Typography;

use ItalyStrap\Tests\Unit\PublicApi\Settings\PresetCommonTrait;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;

final class FontSizeTest extends UnitTestCase
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
