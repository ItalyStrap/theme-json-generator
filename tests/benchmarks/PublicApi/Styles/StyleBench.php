<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Benchmarks\PublicApi\Styles;

use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Settings\NullPresets;
use ItalyStrap\ThemeJsonGenerator\Styles\Typography;
use ItalyStrap\ThemeJsonGenerator\ThemeJson;

final class StyleBench
{
    private Typography $styles;

    public function init(): void
    {
        $this->styles = (new ThemeJson(new Config(), new NullPresets()))->styles()->typography();
    }

    /**
     * @Revs(10000)
     * @Iterations(5)
     * @BeforeMethods({"init"})
     */
    public function benchProperty(): void
    {
        $this->styles
            ->property('fontFamily', 'fontFamily.base')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px')
            ->property('fontSize', '20px');

        \json_encode($this->styles);
    }
}
