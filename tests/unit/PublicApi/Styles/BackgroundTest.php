<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\PublicApi\Styles;

use ItalyStrap\Tests\UnitTestCase;
use ItalyStrap\ThemeJsonGenerator\Styles\Background;

final class BackgroundTest extends UnitTestCase
{
    use CommonTests;

    protected function makeInstance(): Background
    {
        return $this->makeStyles()->background();
    }

    public function testItShouldCreateCorrectArray(): void
    {
        $result = $this->makeInstance()
            ->backgroundImage('url(hero.jpg)')
            ->backgroundPosition('center')
            ->backgroundRepeat('no-repeat')
            ->backgroundSize('cover')
            ->backgroundAttachment('fixed')
            ->toArray();

        $this->assertSame([
            'backgroundImage' => 'url(hero.jpg)',
            'backgroundPosition' => 'center',
            'backgroundRepeat' => 'no-repeat',
            'backgroundSize' => 'cover',
            'backgroundAttachment' => 'fixed',
        ], $result);
    }
}
