<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Styles;

use ItalyStrap\Tests\Unit\Domain\Input\Styles\CssTest;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings\NullPresets;
use Sabberworm\CSS\Parser;
use Sabberworm\CSS\Parsing\SourceException;

/**
 * @link https://make.wordpress.org/core/2023/03/06/custom-css-for-global-styles-and-per-block/
 * @link https://fullsiteediting.com/lessons/how-to-use-custom-css-in-theme-json/
 * @link https://developer.wordpress.org/news/2023/04/21/per-block-css-with-theme-json/
 * @link https://github.com/WordPress/wordpress-develop/blob/trunk/tests/phpunit/tests/theme/wpThemeJson.php
 * @link https://developer.wordpress.org/themes/global-settings-and-styles/
 *
 * @link https://www.google.it/search?q=php+inline+css+content&sca_esv=596560865&ei=mAicZaTCGp3Axc8Pq7yT8AQ&ved=0ahUKEwik7p-Rgs6DAxUdYPEDHSveBE4Q4dUDCBA&uact=5&oq=php+inline+css+content&gs_lp=Egxnd3Mtd2l6LXNlcnAiFnBocCBpbmxpbmUgY3NzIGNvbnRlbnQyBRAhGKABMgUQIRigATIIECEYFhgeGB0yCBAhGBYYHhgdMggQIRgWGB4YHUjvogFQmgdYwJcBcAF4AZABAJgBsQGgAZkSqgEEMC4xOLgBA8gBAPgBAcICChAAGEcY1gQYsAPCAgoQABiABBiKBRhDwgIFEAAYgATCAgYQABgWGB7CAgcQABiABBgTwgIIEAAYFhgeGBPiAwQYACBBiAYBkAYI&sclient=gws-wiz-serp#ip=1
 * @link https://github.com/topics/inline-css?l=php
 * @link https://github.com/sabberworm/PHP-CSS-Parser
 *
 * @psalm-api
 * @see CssTest
 */
class Css
{
    public const M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING = 'CSS cannot begin with an ampersand (&)';

    private PresetsInterface $presets;
    private bool $isCompressed = true;

    public function __construct(
        PresetsInterface $presets = null
    ) {
        $this->presets = $presets ?? new NullPresets();
    }

    public function expanded(): self
    {
        $this->isCompressed = false;
        return $this;
    }

    /**
     * @deprecated Use parse() instead
     */
    public function parseString(string $css, string $selector = ''): string
    {
        if (\str_starts_with(\trim($css), '&')) {
            throw new \RuntimeException(self::M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING);
        }

        $css = $this->presets->parse($css);
        $css = $this->duplicateRulesForSelectorList($css);

        if ($selector === '') {
            return $css;
        }

        $exploded = \explode($selector, $css);

        $rootRule = [];
        $explodedNew = [];
        foreach ($exploded as $key => $value) {
            // @todo remove after PHP >= 8
            // phpcs:disable
            if (\str_starts_with(\trim($value), '{')) {
                $value = \str_replace(['{', '}'], '', \rtrim($value));
                $value = \preg_replace('/^ +/m', '', $value);
                $rootRule[] = $value;
                continue;
            }

            // phpcs:enable

            $explodedNew[$key] = $value;
        }

        return \ltrim(\implode('', $rootRule) . \implode('&', $explodedNew), "\t\n\r\0\x0B&");
    }

    /**
     * @throws SourceException
     */
    public function parse(string $css, string $selector = ''): string
    {
        if (\str_starts_with(\trim($css), '&')) {
            throw new \RuntimeException(self::M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING);
        }

        $css = $this->presets->parse($css);
        $selector = \trim($selector);

        if ($selector === '') {
            return $css;
        }

        $parser = new Parser($css);
        $doc = $parser->parse();

        $rootRules = '';
        $additionalSelectors = [];

        $newLine = $this->isCompressed ? '' : PHP_EOL;
        $newLineAfterBlock = $this->isCompressed ? '' : PHP_EOL . PHP_EOL;
        $space = $this->isCompressed ? '' : \implode('', \array_fill(0, 4, ' '));
        $spaceAfterSelector = $this->isCompressed ? '' : ' ';

        foreach ($doc->getAllDeclarationBlocks() as $declarationBlock) {
            foreach ($declarationBlock->getSelectors() as $cssSelector) {
                if (\is_string($cssSelector)) {
                    $cssSelector = new \Sabberworm\CSS\Property\Selector($cssSelector);
                }

                if ($cssSelector->getSelector() === $selector) {
                    foreach ($declarationBlock->getRules() as $rule) {
                        $ruleText = $rule->getRule() . ': ' . (string)$rule->getValue() . ';' . $newLine;
                        $rootRules .= $ruleText;
                    }

                    continue;
                }

                $actualSelector = $cssSelector->getSelector();
                $newSelector = \substr($actualSelector, \strlen($selector));

                $cssBlock = $newSelector . $spaceAfterSelector . '{' . $newLine;
                foreach ($declarationBlock->getRules() as $rule) {
                    $cssBlock .= $space . $rule->getRule() . ': ' . (string)$rule->getValue() . ';' . $newLine;
                }
                $cssBlock .= '}' . $newLineAfterBlock;
                $additionalSelectors[] = $cssBlock;
            }
        }

        \array_unshift($additionalSelectors, $rootRules . $newLine);
        return \trim(\implode('&', $additionalSelectors), "\t\n\r\0\x0B&");
    }

    /**
     * Right now the algorithm used by WordPress to apply custom CSS does not convert selector list
     * correctly, so I need to duplicate the rules for each selector in the list.
     */
    private function duplicateRulesForSelectorList(string $css): string
    {
        $pattern = '/\{(.*)}/s';
        \preg_match($pattern, $css, $matches);

        if (!isset($matches[1])) {
            return $css;
        }


        $pos = \strpos($css, '{');
        if ($pos === false) {
            return $css;
        }

        $selectors = \substr($css, 0, $pos);
        $selectorArray = \explode(',', $selectors);

        if (\count($selectorArray) === 1) {
            return $css;
        }

        $lastSelector = \array_pop($selectorArray);

        $cssFinal = "";
        $rules = $matches[0];

        foreach ($selectorArray as $selector) {
            $cssFinal .= \rtrim($selector) . " $rules\n";
        }

        $cssFinal .= \rtrim($lastSelector) . " $rules\n";

        return $cssFinal;
    }
}
