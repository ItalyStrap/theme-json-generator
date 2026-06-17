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

    #[ThemeSchemaCoverage(['styles', 'css'])]
    public function css(string $css, string $selector = ''): self
    {
        return $this->writeOrFail('css', $this->css->parse($css, $selector));
    }

    #[ThemeSchemaCoverage(['styles', 'css'])]
    public function appendCss(string $css, string $selector = ''): self
    {
        return $this->appendParsedCss($this->css->parse($css, $selector));
    }

    #[ThemeSchemaCoverage(['styles', 'css'])]
    public function scss(string $scss, string $selector = ''): self
    {
        return $this->writeOrFail('css', $this->scss->parse($scss, $selector));
    }

    #[ThemeSchemaCoverage(['styles', 'css'])]
    public function appendScss(string $scss, string $selector = ''): self
    {
        return $this->appendParsedCss($this->scss->parse($scss, $selector));
    }

    private function appendParsedCss(string $parsedCss): self
    {
        $currentCss = $this->read('css');
        $currentCss = \is_string($currentCss) ? $currentCss : '';

        return $this->writeOrFail('css', $currentCss . $this->cssAppendSeparator($currentCss, $parsedCss) . $parsedCss);
    }

    private function cssAppendSeparator(string $currentCss, string $parsedCss): string
    {
        if ($currentCss === '') {
            return '';
        }

        return \preg_match('/^(?:\s|[&.:#\[>+~*])/', $parsedCss) === 1 ? '&' : '';
    }

    #[ThemeSchemaCoverage(['styles', 'background'])]
    public function background(): Background
    {
        return new Background($this->presets, [], $this->context->at('background'));
    }

    #[ThemeSchemaCoverage(['styles', 'border'])]
    public function border(): Border
    {
        return new Border($this->presets, [], $this->context->at('border'));
    }

    #[ThemeSchemaCoverage(['styles', 'color'])]
    public function color(): Color
    {
        return new Color($this->presets, [], $this->context->at('color'));
    }

    #[ThemeSchemaCoverage(['styles', 'dimensions'])]
    public function dimensions(): Dimensions
    {
        return new Dimensions($this->presets, [], $this->context->at('dimensions'));
    }

    #[ThemeSchemaCoverage(['styles', 'filter'])]
    public function filter(): Filter
    {
        return new Filter($this->presets, [], $this->context->at('filter'));
    }

    #[ThemeSchemaCoverage(['styles', 'outline'])]
    public function outline(): Outline
    {
        return new Outline($this->presets, [], $this->context->at('outline'));
    }

    #[ThemeSchemaCoverage(['styles', 'shadow'])]
    public function shadow(string $value): self
    {
        return $this->writeOrFail('shadow', $this->parseStyleValue($value));
    }

    #[ThemeSchemaCoverage(['styles', 'spacing'])]
    public function spacing(): Spacing
    {
        return new Spacing($this->presets, [], $this->context->at('spacing'));
    }

    #[ThemeSchemaCoverage(['styles', 'typography'])]
    public function typography(): Typography
    {
        return new Typography($this->presets, [], $this->context->at('typography'));
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    #[ThemeSchemaCoverage(['styles', 'elements'])]
    #[ThemeSchemaCoverage(['styles', 'elements', '*'])]
    public function elements(array|string $path): self
    {
        return new self($this->presets, $this->context->elements($path), $this->css, $this->scss);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    #[ThemeSchemaCoverage(['styles', 'blocks'])]
    #[ThemeSchemaCoverage(['styles', 'blockTargets', '*'])]
    public function blocks(array|string $path): self
    {
        return new self($this->presets, $this->context->blocks($path), $this->css, $this->scss);
    }

    #[ThemeSchemaCoverage(['styles', 'variations'])]
    public function variations(string $variation): self
    {
        return new self(
            $this->presets,
            $this->context->variations($variation),
            $this->css,
            $this->scss
        );
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
