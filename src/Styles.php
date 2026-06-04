<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Styles\Background;
use ItalyStrap\ThemeJsonGenerator\Styles\Border;
use ItalyStrap\ThemeJsonGenerator\Styles\Color;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Styles\CssInterface;
use ItalyStrap\ThemeJsonGenerator\Styles\Dimensions;
use ItalyStrap\ThemeJsonGenerator\Styles\Filter;
use ItalyStrap\ThemeJsonGenerator\Styles\Outline;
use ItalyStrap\ThemeJsonGenerator\Styles\Spacing;
use ItalyStrap\ThemeJsonGenerator\Styles\Typography;

final readonly class Styles
{
    private CssInterface $css;

    private StyleContext $context;

    public function __construct(
        private ThemeJson $themeJson,
        private PresetsInterface $presets,
        ?StyleContext $context = null,
        ?CssInterface $css = null,
    ) {
        $this->css = $css ?? new Css($this->presets);
        $this->context = $context ?? new StyleContext($this->themeJson, [SectionNames::STYLES]);
    }

    public function css(string $css, string $selector = ''): bool
    {
        return $this->set('css', $this->css->parse($css, $selector));
    }

    public function appendCss(string $css, string $selector = ''): bool
    {
        $currentCss = $this->get('css');
        $currentCss = \is_string($currentCss) ? $currentCss : '';

        $parsedCss = $this->css->parse($css, $selector);
        $separator = $currentCss !== '' && \preg_match('/^(?:\s|[&.:#\[>+~*])/', $parsedCss) === 1 ? '&' : '';

        return $this->set('css', $currentCss . $separator . $parsedCss);
    }

    public function background(): Background
    {
        return new Background($this->presets, [], $this->context->at('background'));
    }

    public function border(): Border
    {
        return new Border($this->presets, [], $this->context->at('border'));
    }

    public function color(): Color
    {
        return new Color($this->presets, [], $this->context->at('color'));
    }

    public function dimensions(): Dimensions
    {
        return new Dimensions($this->presets, [], $this->context->at('dimensions'));
    }

    public function filter(): Filter
    {
        return new Filter($this->presets, [], $this->context->at('filter'));
    }

    public function outline(): Outline
    {
        return new Outline($this->presets, [], $this->context->at('outline'));
    }

    public function shadow(string $value): bool
    {
        return $this->context->set('shadow', $this->parseStyleValue($value));
    }

    public function spacing(): Spacing
    {
        return new Spacing($this->presets, [], $this->context->at('spacing'));
    }

    public function typography(): Typography
    {
        return new Typography($this->presets, [], $this->context->at('typography'));
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function elements(array|string $path): self
    {
        return new self($this->themeJson, $this->presets, $this->context->elements($path), $this->css);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function blocks(array|string $path): self
    {
        return new self($this->themeJson, $this->presets, $this->context->blocks($path), $this->css);
    }

    public function variations(string $variation): self
    {
        return new self($this->themeJson, $this->presets, $this->context->variations($variation), $this->css);
    }

    /**
     * @param array<array-key, string|int>|string $path
     * @TODO Investigate whether this generic escape hatch should remain public.
     */
    public function set(array|string $path, mixed $value): bool
    {
        if (\is_string($path)) {
            $path = \explode('.', $path);
        }

        return $this->context->set($path, $value);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function get(array|string $path, mixed $default = null): mixed
    {
        if (\is_string($path)) {
            $path = \explode('.', $path);
        }

        return $this->context->get($path, $default);
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
