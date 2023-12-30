<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Domain\Settings;

use ItalyStrap\Tests\UnitTestCase;

class CommonTest extends UnitTestCase
{
    use \ItalyStrap\ThemeJsonGenerator\Domain\Settings\CommonTrait;

    public static function invalidSlugProvider()
    {
        yield 'empty' => [''];
        yield 'with space' => ['with space'];
//      yield 'with uppercase' => ['withUpperCase'];
    }

    /**
     * @dataProvider invalidSlugProvider
     */
    public function testIsValidSlug(string $slug): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->assertValidSlug($slug);
    }
}
