<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi;

use ItalyStrap\Config\Config;
use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\CustomTemplates;
use ItalyStrap\ThemeJsonGenerator\Settings\Presets;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class CustomTemplatesTest extends UnitTestCase
{
    private function makeInstance(): ThemeJson
    {
        return new ThemeJson(new Config(), new Presets());
    }

    public function testItShouldAddCustomTemplatesWithRequiredFields(): void
    {
        $sut = $this->makeInstance();

        $result = $sut->customTemplates()->addTemplate('landing', 'Landing');

        $this->assertInstanceOf(CustomTemplates::class, $result);
        $this->assertSame(
            [
                [
                    'name' => 'landing',
                    'title' => 'Landing',
                ],
            ],
            $sut->get('customTemplates')
        );
    }

    public function testItShouldAddCustomTemplatesWithPostTypes(): void
    {
        $sut = $this->makeInstance();

        $sut->customTemplates()->addTemplate('landing', 'Landing', ['page', 'post']);

        $this->assertSame(
            [
                [
                    'name' => 'landing',
                    'title' => 'Landing',
                    'postTypes' => ['page', 'post'],
                ],
            ],
            $sut->get('customTemplates')
        );
    }

    public function testItShouldRejectEmptyNames(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty custom template name.');

        $this->makeInstance()->customTemplates()->addTemplate('', 'Landing');
    }

    public function testItShouldRejectEmptyTitles(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a non-empty custom template title.');

        $this->makeInstance()->customTemplates()->addTemplate('landing', '');
    }

    public function testItShouldRejectEmptyPostTypes(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected custom template post types to contain non-empty values.');

        $this->makeInstance()->customTemplates()->addTemplate('landing', 'Landing', ['']);
    }
}
