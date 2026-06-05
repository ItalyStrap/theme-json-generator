<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\TemplateParts;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class TemplatePartsTest extends UnitTestCase
{
    private function makeInstance(): ThemeJson
    {
        return new ThemeJson(new Config(), new Presets());
    }

    public function testItShouldAddTemplatePartsWithExplicitFields(): void
    {
        $sut = $this->makeInstance();

        $result = $sut->templateParts()->addPart('header', 'header', 'Header');

        $this->assertInstanceOf(TemplateParts::class, $result);
        $this->assertSame(
            [
                [
                    'name' => 'header',
                    'title' => 'Header',
                    'area' => 'header',
                ],
            ],
            $sut->get('templateParts')
        );
    }

    public function testItShouldUseTheDefaultArea(): void
    {
        $sut = $this->makeInstance();

        $sut->templateParts()->addPart('sidebar');

        $this->assertSame(
            [
                [
                    'name' => 'sidebar',
                    'area' => 'uncategorized',
                ],
            ],
            $sut->get('templateParts')
        );
    }

    public function testItShouldRejectEmptyNames(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty template part name.');

        $this->makeInstance()->templateParts()->addPart('');
    }

    public function testItShouldRejectEmptyAreas(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty template part area.');

        $this->makeInstance()->templateParts()->addPart('header', '');
    }
}
