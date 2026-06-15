<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Settings;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Palette;
use ItalyStrap\ThemeJsonGenerator\Settings\Color\Utilities\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography\FontSize;

final class PresetsTest extends UnitTestCase
{
    private function makeInstance(): Presets
    {
        return new Presets();
    }

    public function testItShouldGet(): void
    {
        $sut = $this->makeInstance();
        $sut->addMultiple([$this->prepareFakeItem('1')]);

        $this->assertInstanceOf(PresetInterface::class, $sut->get('category1.slug1'));
        $this->assertSame('var1', (string)$sut->get('category1.slug1'));
        $this->assertSame('var1', (string)$sut->get(['category1', 'slug1']));
        $this->assertNull($sut->get('category1.slug2'));
        $this->assertSame('default', $sut->get('category1.slug2', 'default'));
        $this->assertSame('default', $sut->get(['category1', 'slug2'], 'default'));
    }

    public function testItShouldParse(): void
    {
        $sut = $this->makeInstance();
        $sut->addMultiple([
            $this->prepareFakeItem('1'),
            $this->prepareFakeItem('2'),
        ]);

        $this->assertSame('var1', $sut->parse('{{category1.slug1}}'));
        $this->assertSame('var1var2', $sut->parse('{{category1.slug1}}{{category2.slug2}}'));

        $this->assertSame('passThrough', $sut->parse('passThrough'));
        $this->assertSame('', $sut->parse(''));
    }

    public function testItShouldReturnTheCollection(): void
    {
        $sut = $this->makeInstance();
        $sut->add($this->prepareFakeItem('1'));

        $collection = $sut->collection();

        $this->assertSame($sut->get('category1.slug1'), $collection['category1']['slug1']);
    }

    public function testItShouldCreatePresetHelpers(): void
    {
        $sut = $this->makeInstance();

        $sut->addMultiple([
            new Palette('base', 'Base', new Color('#ffffff')),
            new FontSize('base', 'Base', '1rem'),
            new Custom('spacing.base', '1rem'),
        ]);

        $this->assertSame('#ffffff', $sut->get('color.base')->toArray()['color']);
        $this->assertSame('#ffffff', $sut->get(['color', 'base'])->toArray()['color']);
        $this->assertSame('var(--wp--preset--font-size--base)', $sut->get('fontSize.base')->var());
        $this->assertSame('1rem', (string)$sut->get('custom.spacing.base'));
        $this->assertSame('1rem', (string)$sut->get(['custom', 'spacing', 'base']));
    }

    public function testItShouldUseDotNotationAsNestedPresetPath(): void
    {
        $sut = $this->makeInstance();

        $sut->add(new Palette('brand.primary', 'Brand Primary', new Color('#111111')));

        $this->assertSame('#111111', $sut->get('color.brand.primary')->toArray()['color']);
        $this->assertSame('#111111', $sut->get(['color', 'brand', 'primary'])->toArray()['color']);
        $this->assertSame('fallback', $sut->get(['color', 'brand.secondary'], 'fallback'));
    }

    public function testItShouldRejectDottedSlugWhenParentPresetAlreadyExists(): void
    {
        $sut = $this->makeInstance();
        $sut->add(new Custom('spacer', '1rem'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot register custom.spacer.base because custom.spacer is already a preset.');

        $sut->add(new Custom('spacer.base', '2rem'));
    }

    public function testItShouldRejectDuplicatedPresetPath(): void
    {
        $sut = $this->makeInstance();
        $sut->add(new Custom('spacer', '1rem'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Preset path custom.spacer is already registered.');

        $sut->add(new Custom('spacer', '2rem'));
    }

    public function testItShouldRejectParentPresetWhenDottedSlugAlreadyExists(): void
    {
        $sut = $this->makeInstance();
        $sut->add(new Custom('spacer.base', '1rem'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(
            'Cannot register custom.spacer because custom.spacer already contains nested presets.'
        );

        $sut->add(new Custom('spacer', '2rem'));
    }

    public function testItShouldStoreBlockScopedPresetsWithoutOverwritingRootPresets(): void
    {
        $sut = $this->makeInstance();
        $sut->add(new Palette('base', 'Base', new Color('#ffffff')));
        $sut->addToBlock('core/group', new Palette('base', 'Block Base', new Color('#000000')));

        $collection = $sut->collection();

        $this->assertSame('#ffffff', $sut->get('color.base')->toArray()['color']);
        $this->assertSame('#000000', $collection['blocks']['core/group']['color']['base']->toArray()['color']);
    }

    private function prepareFakeItem(string $val = ''): PresetInterface
    {
        return new class ($val) implements PresetInterface {
            public function __construct(private readonly string $val = '')
            {
            }

            public function slug(): string
            {
                return 'slug' . $this->val;
            }

            public function ref(): string
            {
                return 'ref' . $this->val;
            }

            public function prop(): string
            {
                return 'prop' . $this->val;
            }

            public function var(string $fallback = ''): string
            {
                return $fallback !== '' ? $fallback : 'var' . $this->val;
            }

            public function __toString(): string
            {
                return $this->var();
            }

            public function toArray(): array
            {
                return [
                    'slug' => $this->slug(),
                    'ref' => $this->ref(),
                    'prop' => $this->prop(),
                    'var' => $this->var(),
                ];
            }

            public function type(): string
            {
                return 'category' . $this->val;
            }
        };
    }
}
