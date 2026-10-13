<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Unit\Tools;

use ItalyStrap\Tests\Benchmarks\PublicApi\Styles\StyleBench;
use ItalyStrap\Tests\UnitTestCase;

final class StyleBenchmarkTest extends UnitTestCase
{
    public function testItShouldInitializeAndRunTheStyleBenchmark(): void
    {
        require_once \codecept_root_dir('tests/benchmarks/PublicApi/Styles/StyleBench.php');
        $benchmark = new StyleBench();
        $benchmark->init();
        $benchmark->benchProperty();

        $property = new \ReflectionProperty(StyleBench::class, 'styles');
        $styles = $property->getValue($benchmark);
        $this->assertInstanceOf(\JsonSerializable::class, $styles);
        $this->assertJsonStringEqualsJsonString(
            '{"fontFamily":"fontFamily.base"}',
            \json_encode($styles, \JSON_THROW_ON_ERROR)
        );
    }
}
