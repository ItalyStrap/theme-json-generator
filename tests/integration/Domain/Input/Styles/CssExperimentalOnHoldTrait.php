<?php

declare(strict_types=1);

namespace ItalyStrap\Tests\Integration\Domain\Input\Styles;

use PHPUnit\Framework\Assert;

trait CssExperimentalOnHoldTrait
{
    public static function styleProvider(): iterable
    {
        yield 'root element with multiple rules and pseudo class and pseudo element' => [
            'actual' => 'height: 100%;width: 100%;color: red;:hover {color: red;}::placeholder {color: red;}',
            'expected' =>
                'height: 100%;width: 100%;color: red;'
                . PHP_EOL . '&:hover {color: red;}'
                . PHP_EOL . '&::placeholder {color: red;}',
        ];

        yield 'pseudo single class' => [
            'actual' => ':hover {color: red;}',
            'expected' => '&:hover {color: red;}',
        ];

        yield 'pseudo single class with multiple rules' => [
            'actual' => ':hover {color: red;height: 100%;}',
            'expected' => '&:hover {color: red;height: 100%;}',
        ];
        yield 'pseudo single class with multiple rules and pseudo element' => [
            'actual' => ':hover {color: red;height: 100%;}::placeholder {color: red;}',
            'expected' => '&:hover {color: red;height: 100%;}' . PHP_EOL . '&::placeholder {color: red;}',
        ];

        yield 'simple pseudo element ::placeholder ' => [
            'actual' => '::placeholder {color: red;}',
            'expected' => '&::placeholder {color: red;}',
        ];

        yield 'simple pseudo element with multiple rules ::placeholder ' => [
            'actual' => '::placeholder {color: red;height: 100%;}',
            'expected' => '&::placeholder {color: red;height: 100%;}',
        ];

        yield 'root element with pseudo element' => [
            'actual' => 'height: 100%;::placeholder {color: red;}',
            'expected' => 'height: 100%;' . PHP_EOL . '&::placeholder {color: red;}',
        ];

        yield 'mixed css example' => [
            'actual' => <<<'MIXED_CSS'
height: 100%;
.foo {
    color: red;
}
MIXED_CSS,
            'expected' => <<<'MIXED_CSS'
height: 100%;
& .foo {
    color: red;
}
MIXED_CSS,
        ];

        yield 'mixed css example with multiple rules' => [
            'actual' => <<<'MIXED_CSS'
height: 100%;
width: 100%;
.foo {
    color: red;
    height: 100%;
}
MIXED_CSS,
            'expected' => <<<'MIXED_CSS'
height: 100%;
width: 100%;
& .foo {
    color: red;
    height: 100%;
}
MIXED_CSS,
        ];

        yield 'simple css example' => [
            'actual' => <<<'CSS'
.foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
}
CSS,
            'expected' => <<<'CSS'
& .foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
}
CSS,
        ];

        yield 'simple css example with multiple rules' => [
            'actual' => <<<'CSS'
.foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
.foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
            'expected' => <<<'CSS'
& .foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& .foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
        ];

        yield 'simple css example with multiple rules and pseudo class' => [
            'actual' => <<<'CSS'
.foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
.foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
:hover {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
            'expected' => <<<'CSS'
& .foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& .foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& table {
    border-collapse: collapse;
    border-spacing: 0;
}
&:hover {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
        ];

        yield 'simple css example with multiple rules and pseudo class and pseudo element' => [
            'actual' => <<<'CSS'
.foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
.foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
table {
    border-collapse: collapse;
    border-spacing: 0;
}
:hover {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
::placeholder {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
            'expected' => <<<'CSS'
& .foo {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& .foo .bar {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
& table {
    border-collapse: collapse;
    border-spacing: 0;
}
&:hover {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
&::placeholder {
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
    color: red;
}
CSS,
        ];

        yield 'root custom properties' => [
            'actual' => <<<'CUSTOM_CSS'
--foo: 100%;
CUSTOM_CSS,
            'expected' => <<<'CUSTOM_CSS'
--foo: 100%;
CUSTOM_CSS,
        ];

        yield 'root custom properties with multiple rules' => [
            'actual' => <<<'CUSTOM_CSS'
--foo: 100%;
--bar: 100%;
CUSTOM_CSS,
            'expected' => <<<'CUSTOM_CSS'
--foo: 100%;
--bar: 100%;
CUSTOM_CSS,
        ];

        yield 'root custom properties mixed with css' => [
            'actual' => <<<'CUSTOM_CSS'
#firstParagraph {
    background-color: var(--first-color);
    color: var(--second-color);
}
--foo: 100%;
--bar: 100%;
.foo {
    --bar: 50%;
    color: red;
    width: var(--foo);
    height: var(--bar);
}
CUSTOM_CSS,
            'expected' => <<<'CUSTOM_CSS'
& #firstParagraph {
    background-color: var(--first-color);
    color: var(--second-color);
}
--foo: 100%;
--bar: 100%;
& .foo {
    --bar: 50%;
    color: red;
    width: var(--foo);
    height: var(--bar);
}
CUSTOM_CSS,
        ];
    }

    /**
     * @dataProvider styleProvider
     */
    public function testItShouldParse(string $css, string $expected): void
    {
        $actual = $this->parse($css);
        Assert::assertSame($expected, $actual);
    }

    private function parse(string $css): string
    {
//        $css = $this->collection->parse($css);
        $rules = \explode("}", \trim($css));
        $rules = \array_filter($rules);

        if ($rules === []) {
            return $css;
        }

        $processedRules = [];
        foreach ($rules as $rule) {
            $el = \explode("{", $rule);
            $el = \array_filter($el);

            /**
             * @var array<string> $processedRule
             */
            $processedRule = $this->getRuleElements($el);

            $processedRules[] = \implode("{", $processedRule);
        }

        $endBracket = $this->hasAmpersand($processedRules) ? '}' : '';

        return \trim(\implode('}', $processedRules) . $endBracket);
    }

    private function getRuleElements(array $el): array
    {
        $processedRule = [];
        /**
         * @var string $value
         */
        foreach ($el as $value) {
            $subValues = \explode(";", $value);
            $subValues = \array_filter($subValues);

            /**
             * @var array<string> $processedSubValues
             */
            $processedSubValues = $this->getProcessedSubValues($subValues);

            $endSemicolon = ($this->hasAmpersand($processedSubValues)
                || $this->elementsHaveSpace($processedSubValues))
                ? ''
                : ';';
            $processedRule[] = \implode(";", $processedSubValues) . $endSemicolon;
        }

        return $processedRule;
    }

    private function getProcessedSubValues(array $subValues): array
    {
        $processedSubValues = [];
        /**
         * @var string $subValue
         */
        foreach ($subValues as $subValue) {
            $isPseudoClass = (bool)\preg_match('/(?<!\w):(?!:)/', $subValue);
            if (\strpos($subValue, ":") !== false && !$isPseudoClass) {
                $processedSubValues[] = $subValue;
                continue;
            }

            if ($this->hasOnlySpace($subValue)) {
                $processedSubValues[] = $subValue;
                continue;
            }

            $space = $isPseudoClass ? '' : ' ';
            $processedSubValues[] = PHP_EOL . '&' . $space . \ltrim($subValue, " \t\n\r\0\x0B");
        }

        return $processedSubValues;
    }

    private function hasAmpersand(array $processedRules): bool
    {
        /**
         * @var string $processedRule
         */
        foreach (\array_filter($processedRules) as $processedRule) {
            if (\strpos($processedRule, '&') !== false) {
                return true;
            }
        }

        return false;
    }

    private function hasOnlySpace(string $subValue): bool
    {
        return \trim($subValue, " \t\n\r\0\x0B") === '';
    }

    private function elementsHaveSpace(array $elements): bool
    {
        /**
         * @var string $element
         */
        foreach ($elements as $element) {
            if ($this->hasOnlySpace($element)) {
                return true;
            }
        }

        return false;
    }
}
