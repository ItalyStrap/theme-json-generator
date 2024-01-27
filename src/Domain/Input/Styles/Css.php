<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

use ItalyStrap\Tests\Unit\Domain\Input\Styles\CssTest;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\CollectionInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\NullCollection;

/**
 * @psalm-api
 * @see CssTest
 */
class Css
{
    private CollectionInterface $collection;

    public function __construct(
        CollectionInterface $collection = null
    ) {
        $this->collection = $collection ?? new NullCollection();
    }

    public function parseString(string $css): string
    {
        $css = $this->collection->parse($css);
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
