<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Settings\Background;
use ItalyStrap\ThemeJsonGenerator\Settings\Border;
use ItalyStrap\ThemeJsonGenerator\Settings\Color;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom;
use ItalyStrap\ThemeJsonGenerator\Settings\Dimensions;
use ItalyStrap\ThemeJsonGenerator\Settings\Layout;
use ItalyStrap\ThemeJsonGenerator\Settings\Lightbox;
use ItalyStrap\ThemeJsonGenerator\Settings\Position;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\Shadow;
use ItalyStrap\ThemeJsonGenerator\Settings\Spacing;
use ItalyStrap\ThemeJsonGenerator\Settings\Typography;
use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;

final readonly class Settings
{
    private SettingsContext $context;

    public function __construct(
        private ThemeJson $themeJson,
        private PresetsInterface $presets,
        ?SettingsContext $context = null,
    ) {
        $this->context = $context ?? new SettingsContext($this->themeJson, [SectionNames::SETTINGS]);
    }

    #[ThemeSchemaCoverage(['settings', 'appearanceTools'])]
    public function enableAppearanceTools(): self
    {
        $this->set('appearanceTools', true);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'appearanceTools'])]
    public function disableAppearanceTools(): self
    {
        $this->set('appearanceTools', false);
        return $this;
    }

    #[ThemeSchemaCoverage(['settings', 'color'])]
    public function color(): Color
    {
        return new Color($this);
    }

    #[ThemeSchemaCoverage(['settings', 'background'])]
    public function background(): Background
    {
        return new Background($this);
    }

    #[ThemeSchemaCoverage(['settings', 'border'])]
    public function border(): Border
    {
        return new Border($this);
    }

    #[ThemeSchemaCoverage(['settings', 'dimensions'])]
    public function dimensions(): Dimensions
    {
        return new Dimensions($this);
    }

    #[ThemeSchemaCoverage(['settings', 'layout'])]
    public function layout(): Layout
    {
        return new Layout($this, $this->presets);
    }

    #[ThemeSchemaCoverage(['settings', 'lightbox'])]
    public function lightbox(): Lightbox
    {
        return new Lightbox($this);
    }

    #[ThemeSchemaCoverage(['settings', 'position'])]
    public function position(): Position
    {
        return new Position($this);
    }

    #[ThemeSchemaCoverage(['settings', 'shadow'])]
    public function shadow(): Shadow
    {
        return new Shadow($this);
    }

    #[ThemeSchemaCoverage(['settings', 'spacing'])]
    public function spacing(): Spacing
    {
        return new Spacing($this);
    }

    #[ThemeSchemaCoverage(['settings', 'typography'])]
    public function typography(): Typography
    {
        return new Typography($this);
    }

    #[ThemeSchemaCoverage(['settings', 'custom'])]
    public function custom(): Custom
    {
        return new Custom($this);
    }

    #[ThemeSchemaCoverage(['settings', 'extraRoots', 'blocks'])]
    #[ThemeSchemaCoverage(['settings', 'blockTargets', '*'])]
    public function blocks(string $block): self
    {
        return new self($this->themeJson, $this->presets, $this->context->blocks($block));
    }

    /**
     * @param array<array-key, string|int>|string $path
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
    public function get(array|string $path, mixed $default = null): mixed
    {
        if (\is_string($path)) {
            $path = \explode('.', $path);
        }

        return $this->context->get($path, $default);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    public function addPreset(array|string $path, PresetInterface $preset): void
    {
        $this->presets->addAt($this->context->path($path), $preset);
    }
}
