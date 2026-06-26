<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\NullPresets;

final class NullPresetsTest extends UnitTestCase
{
    public function testItShouldKeepPresetPlaceholdersUnresolved(): void
    {
        $sut = new NullPresets();

        $this->assertSame(
            '{{color.primary}}',
            $sut->parse('{{color.primary}}')
        );
    }
}
