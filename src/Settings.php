<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
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

final readonly class Settings
{
    /**
     * @var string
     */
    public const SECTION = 'settings';

    public function __construct(
        private PresetsInterface $presets,
        private SettingsContext $context,
    ) {
    }

    #[ThemeSchemaCoverage([self::SECTION, 'appearanceTools'])]
    public function enableAppearanceTools(): self
    {
        $this->write('appearanceTools', true);
        return $this;
    }

    #[ThemeSchemaCoverage([self::SECTION, 'appearanceTools'])]
    public function disableAppearanceTools(): self
    {
        $this->write('appearanceTools', false);
        return $this;
    }

    #[ThemeSchemaCoverage([self::SECTION, 'useRootPaddingAwareAlignments'])]
    public function enableUseRootPaddingAwareAlignments(): self
    {
        $this->write('useRootPaddingAwareAlignments', true);
        return $this;
    }

    #[ThemeSchemaCoverage([self::SECTION, 'useRootPaddingAwareAlignments'])]
    public function disableUseRootPaddingAwareAlignments(): self
    {
        $this->write('useRootPaddingAwareAlignments', false);
        return $this;
    }

    #[ThemeSchemaCoverage([self::SECTION, 'color'])]
    public function color(): Color
    {
        return new Color($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'background'])]
    public function background(): Background
    {
        return new Background($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'border'])]
    public function border(): Border
    {
        return new Border($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'dimensions'])]
    public function dimensions(): Dimensions
    {
        return new Dimensions($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'layout'])]
    public function layout(): Layout
    {
        return new Layout($this, $this->presets);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'lightbox'])]
    public function lightbox(): Lightbox
    {
        return new Lightbox($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'position'])]
    public function position(): Position
    {
        return new Position($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'shadow'])]
    public function shadow(): Shadow
    {
        return new Shadow($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'spacing'])]
    public function spacing(): Spacing
    {
        return new Spacing($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'typography'])]
    public function typography(): Typography
    {
        return new Typography($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'custom'])]
    public function custom(): Custom
    {
        return new Custom($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, 'blocks'])]
    #[ThemeSchemaCoverage([self::SECTION, 'blockTargets', '*'])]
    public function blocks(string $block): self
    {
        return new self($this->presets, $this->context->blocks($block));
    }

    public function addPreset(PresetInterface $preset): void
    {
        $block = $this->context->blockName();
        if ($block === null) {
            $this->presets->add($preset);
            return;
        }

        $this->presets->addToBlock($block, $preset);
    }

    /**
     * @internal
     * @param array<array-key, string|int>|string $path
     */
    public function write(array|string $path, mixed $value): bool
    {
        return $this->context->set($path, $value);
    }

    /**
     * @internal
     * @param array<array-key, string|int>|string $path
     */
    public function read(array|string $path, mixed $default = null): mixed
    {
        return $this->context->get($path, $default);
    }
}
