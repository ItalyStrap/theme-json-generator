<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class SpacingTest extends UnitTestCase
{
    public function testItShouldWriteSpacingSettingsInsideBlockContext(): void
    {
        $presets = new Presets();
        $sut = new ThemeJson(new Config(), $presets);
        $spacing = $sut->settings()->blocks('core/group')->spacing();

        $result = $spacing
            ->disableBlockGapAndLayoutStyles()
            ->enableMargin()
            ->disablePadding()
            ->units('px', 'rem')
            ->disableCustomSpacingSize()
            ->enableDefaultSpacingSizes()
            ->addSpacingSize('50', 'Medium', '1rem');

        $spacing->scale()->operator('*')->increment(1.5)->steps(7)->mediumStep(1.5)->unit('rem');

        $this->assertInstanceOf(Spacing::class, $result);
        $this->assertNull($sut->get('settings.blocks.core/group.spacing.blockGap'));
        $this->assertTrue($sut->get('settings.blocks.core/group.spacing.margin'));
        $this->assertFalse($sut->get('settings.blocks.core/group.spacing.padding'));
        $this->assertSame(['px', 'rem'], $sut->get('settings.blocks.core/group.spacing.units'));
        $this->assertFalse($sut->get('settings.blocks.core/group.spacing.customSpacingSize'));
        $this->assertTrue($sut->get('settings.blocks.core/group.spacing.defaultSpacingSizes'));
        $this->assertSame('*', $sut->get('settings.blocks.core/group.spacing.spacingScale.operator'));
        $this->assertEqualsWithDelta(
            1.5,
            $sut->get('settings.blocks.core/group.spacing.spacingScale.increment'),
            PHP_FLOAT_EPSILON
        );
        $this->assertSame(7, $sut->get('settings.blocks.core/group.spacing.spacingScale.steps'));
        $this->assertEqualsWithDelta(
            1.5,
            $sut->get('settings.blocks.core/group.spacing.spacingScale.mediumStep'),
            PHP_FLOAT_EPSILON
        );
        $this->assertSame('rem', $sut->get('settings.blocks.core/group.spacing.spacingScale.unit'));
        $this->assertSame(
            [
                [
                    'slug' => '50',
                    'name' => 'Medium',
                    'size' => '1rem',
                ],
            ],
            $presets->toArraysByPath()['settings.blocks.core/group.spacing.spacingSizes']
        );
    }

    public function testItShouldRejectEmptyUnits(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected at least one spacing unit.');

        (new ThemeJson(new Config(), new Presets()))->settings()->spacing()->units();
    }
}
