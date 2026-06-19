<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Styles\Background;
use ItalyStrap\ThemeJsonGenerator\Styles\Border;
use ItalyStrap\ThemeJsonGenerator\Styles\Color;
use ItalyStrap\ThemeJsonGenerator\Styles\CssInterface;
use ItalyStrap\ThemeJsonGenerator\Styles\Dimensions;
use ItalyStrap\ThemeJsonGenerator\Styles\Filter;
use ItalyStrap\ThemeJsonGenerator\Styles\Outline;
use ItalyStrap\ThemeJsonGenerator\Styles\Scss;
use ItalyStrap\ThemeJsonGenerator\Styles\Spacing;
use ItalyStrap\ThemeJsonGenerator\Styles\Typography;

final readonly class Styles
{
    /**
     * @var string
     */
    public const SECTION = 'styles';

    public function __construct(
        private PresetsInterface $presets,
        private StyleContext $context,
        private CssInterface $css,
        private Scss $scss,
    ) {
    }

    #[ThemeSchemaCoverage([self::SECTION, 'css'])]
    public function css(string $css, string $selector = ''): self
    {
        return $this->writeOrFail('css', $this->parseCss($css, $selector));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'css'])]
    public function appendCss(string $css, string $selector = ''): self
    {
        return $this->appendParsedCss($this->parseCss($css, $selector), $selector !== '');
    }

    #[ThemeSchemaCoverage([self::SECTION, 'css'])]
    public function scss(string $scss, string $selector = ''): self
    {
        return $this->writeOrFail('css', $this->scss->parse($scss, $selector));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'css'])]
    public function appendScss(string $scss, string $selector = ''): self
    {
        return $this->appendParsedCss($this->scss->parse($scss, $selector), $selector !== '');
    }

    private function parseCss(string $css, string $selector): string
    {
        if (!$this->context->isScoped()) {
            $this->assertRootCssDoesNotStartWithAmpersand($css);
        }

        if ($this->context->isScoped() && \trim($selector) === '') {
            $this->assertNestedSelectorsAreScoped($css);
        }

        return $this->css->parse($css, $selector);
    }

    private function assertRootCssDoesNotStartWithAmpersand(string $css): void
    {
        if (!\str_starts_with(\trim($css), '&')) {
            return;
        }

        throw new \RuntimeException(CssInterface::M_AMPERSAND_MUST_NOT_BE_AT_THE_BEGINNING);
    }

    private function appendParsedCss(string $parsedCss, bool $wasParsedWithSelector): self
    {
        $currentCss = $this->read('css');
        $currentCss = \is_string($currentCss) ? $currentCss : '';

        if (!$this->context->isScoped()) {
            return $this->writeOrFail('css', $currentCss . $parsedCss);
        }

        return $this->writeOrFail(
            'css',
            $currentCss . $this->scopedCssAppendSeparator($currentCss, $parsedCss, $wasParsedWithSelector) . $parsedCss
        );
    }

    private function scopedCssAppendSeparator(
        string $currentCss,
        string $parsedCss,
        bool $wasParsedWithSelector
    ): string {
        if ($currentCss === '' || $parsedCss === '') {
            return '';
        }

        $separator = '';
        $lastCharacter = \substr(\rtrim($currentCss), -1);
        if ($lastCharacter !== ';' && $lastCharacter !== '}') {
            $separator = ';';
        }

        if (!$wasParsedWithSelector) {
            return $separator;
        }

        if (\preg_match('/^(?:\s|[.:#\[>+~*])/', $parsedCss) !== 1) {
            return $separator;
        }

        $newLine = \str_contains($currentCss, PHP_EOL) || \str_contains($parsedCss, PHP_EOL) ? PHP_EOL : '';

        return $separator . $newLine . '&';
    }

    private function assertNestedSelectorsAreScoped(string $css): void
    {
        $offset = 0;

        while (($openingBrace = \strpos($css, '{', $offset)) !== false) {
            $selector = \substr($css, $offset, $openingBrace - $offset);
            $declarationEnd = \strrpos($selector, ';');
            if ($declarationEnd !== false) {
                $selector = \substr($selector, $declarationEnd + 1);
            }

            if (!\str_starts_with(\trim($selector), '&')) {
                throw new \InvalidArgumentException(CssInterface::M_NESTED_SELECTORS_MUST_BE_SCOPED);
            }

            $closingBrace = \strpos($css, '}', $openingBrace + 1);
            if ($closingBrace === false) {
                throw new \InvalidArgumentException('Scoped CSS contains an unclosed declaration block.');
            }

            $offset = $closingBrace + 1;
        }
    }

    #[ThemeSchemaCoverage([self::SECTION, 'background'])]
    public function background(): Background
    {
        return new Background($this->presets, [], $this->context->at('background'));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'border'])]
    public function border(): Border
    {
        return new Border($this->presets, [], $this->context->at('border'));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'color'])]
    public function color(): Color
    {
        return new Color($this->presets, [], $this->context->at('color'));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'dimensions'])]
    public function dimensions(): Dimensions
    {
        return new Dimensions($this->presets, [], $this->context->at('dimensions'));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'filter'])]
    public function filter(): Filter
    {
        return new Filter($this->presets, [], $this->context->at('filter'));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'outline'])]
    public function outline(): Outline
    {
        return new Outline($this->presets, [], $this->context->at('outline'));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'shadow'])]
    public function shadow(string $value): self
    {
        return $this->writeOrFail('shadow', $this->parseStyleValue($value));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'spacing'])]
    public function spacing(): Spacing
    {
        return new Spacing($this->presets, [], $this->context->at('spacing'));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'typography'])]
    public function typography(): Typography
    {
        return new Typography($this->presets, [], $this->context->at('typography'));
    }

    #[ThemeSchemaCoverage([self::SECTION, 'elements'])]
    #[ThemeSchemaCoverage([self::SECTION, 'elements', '*'])]
    public function elements(string $path): self
    {
        return new self($this->presets, $this->context->elements($path), $this->css, $this->scss);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'blocks'])]
    #[ThemeSchemaCoverage([self::SECTION, 'blockTargets', '*'])]
    public function blocks(string $path): self
    {
        if (\preg_match('/^[a-z][a-z0-9-]*\/[a-z][a-z0-9-]*$/', $path) !== 1) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected a valid block name, got "%s".',
                $path
            ));
        }

        return new self($this->presets, $this->context->blocks($path), $this->css, $this->scss);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'variations'])]
    public function variations(string $variation): self
    {
        if (\preg_match('/^[a-z][a-z0-9-]*$/', $variation) !== 1) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected a valid variation slug, got "%s".',
                $variation
            ));
        }

        return new self(
            $this->presets,
            $this->context->variations($variation),
            $this->css,
            $this->scss
        );
    }

    public function state(string $state): self
    {
        if (\preg_match('/^:[a-z][a-z0-9-]*$/', $state) !== 1) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected a valid state, got "%s".',
                $state
            ));
        }

        return new self($this->presets, $this->context->state($state), $this->css, $this->scss);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function write(array|string $path, mixed $value): bool
    {
        return $this->context->set($path, $value);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function writeOrFail(array|string $path, mixed $value): self
    {
        if (!$this->write($path, $value)) {
            throw new \RuntimeException(\sprintf(
                'Unable to write styles property "%s".',
                $this->pathToString($path)
            ));
        }

        return $this;
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function read(array|string $path, mixed $default = null): mixed
    {
        return $this->context->get($path, $default);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function pathToString(array|string $path): string
    {
        if (\is_string($path)) {
            return $path;
        }

        return \implode('.', \array_map(strval(...), $path));
    }

    /**
     * @duplicated snippet
     * @see \ItalyStrap\ThemeJsonGenerator\Styles\CommonTrait::setProperty
     */
    private function parseStyleValue(string $value): string
    {
        $value = $this->presets->get($value, $value);

        if ($value instanceof PresetInterface) {
            $value = $value->var();
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new \RuntimeException(\sprintf(
                'Expected style value to be stringable, got %s.',
                \get_debug_type($value)
            ));
        }

        return $this->presets->parse((string)$value);
    }
}
