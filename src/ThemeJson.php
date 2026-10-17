<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Config\NodeManipulationInterface;
use ItalyStrap\ThemeJsonGenerator\Schema\ThemeSchemaCoverage;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;
use ItalyStrap\ThemeJsonGenerator\Styles\Css;
use ItalyStrap\ThemeJsonGenerator\Styles\Scss;
use ScssPhp\ScssPhp\Compiler;

final readonly class ThemeJson
{
    public const SECTION = 'topLevel';

    /**
     * @var string
     */
    public const SCHEMA = '$schema';

    /**
     * @var string
     */
    public const VERSION = 'version';

    /**
     * @var string
     */
    public const TITLE = 'title';

    /**
     * @var string
     */
    public const SLUG = 'slug';

    /**
     * @var string
     */
    public const DESCRIPTION = 'description';

    /**
     * @param ConfigInterface<array-key, mixed> $config
     */
    public function __construct(
        private ConfigInterface $config,
        private PresetsInterface $presets,
    ) {
    }

    #[ThemeSchemaCoverage([self::SECTION, self::SCHEMA])]
    public function schema(string $schema): self
    {
        return $this->setRoot(self::SCHEMA, $schema);
    }

    #[ThemeSchemaCoverage([self::SECTION, self::VERSION])]
    public function version(int $version): self
    {
        return $this->setRoot(self::VERSION, $version);
    }

    #[ThemeSchemaCoverage([self::SECTION, self::TITLE])]
    public function title(string $title): self
    {
        return $this->setRoot(self::TITLE, $title);
    }

    #[ThemeSchemaCoverage([self::SECTION, self::SLUG])]
    public function slug(string $slug): self
    {
        return $this->setRoot(self::SLUG, $slug);
    }

    #[ThemeSchemaCoverage([self::SECTION, self::DESCRIPTION])]
    public function description(string $description): self
    {
        return $this->setRoot(self::DESCRIPTION, $description);
    }

    #[ThemeSchemaCoverage([self::SECTION, Settings::SECTION])]
    public function settings(): Settings
    {
        return new Settings($this->presets, new SettingsContext($this, [Settings::SECTION]));
    }

    #[ThemeSchemaCoverage([self::SECTION, Styles::SECTION])]
    public function styles(): Styles
    {
        $css = new Css($this->presets);

        return new Styles(
            $this->presets,
            new StyleContext($this, [Styles::SECTION]),
            $css,
            new Scss($css, new Compiler(), $this->presets),
        );
    }

    #[ThemeSchemaCoverage([self::SECTION, BlockTypes::SECTION])]
    public function blockTypes(): BlockTypes
    {
        return new BlockTypes($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, CustomTemplates::SECTION])]
    public function customTemplates(): CustomTemplates
    {
        return new CustomTemplates($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, TemplateParts::SECTION])]
    public function templateParts(): TemplateParts
    {
        return new TemplateParts($this);
    }

    #[ThemeSchemaCoverage([self::SECTION, Patterns::SECTION])]
    public function patterns(): Patterns
    {
        return new Patterns($this);
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    public function set(string|array $key, mixed $value): self
    {
        if (!$this->setConfigValue($key, $value)) {
            throw new \RuntimeException(\sprintf(
                'Unable to set theme.json property "%s".',
                $this->pathToString($key)
            ));
        }

        return $this;
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    public function get(string|array $key, mixed $default = null): mixed
    {
        /** @phpstan-ignore-next-line Config accepts array paths, StoreInterface only advertises string keys. */
        return $this->config->get($key, $default);
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    public function appendTo(string|array $key, mixed $value): self
    {
        if (!$this->nodeManipulation()->appendTo($key, $value)) {
            throw new \RuntimeException(\sprintf(
                'Unable to append theme.json property "%s".',
                $this->pathToString($key)
            ));
        }

        return $this;
    }

    /**
     * @param array<array-key, mixed> ...$arrays
     */
    public function merge(array ...$arrays): void
    {
        $this->config->merge(...$arrays);
    }

    private function setRoot(string $key, mixed $value): self
    {
        return $this->set($key, $value);
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    private function setConfigValue(string|array $key, mixed $value): bool
    {
        /** @phpstan-ignore-next-line Config accepts array paths, StoreInterface only advertises string keys. */
        return $this->config->set($key, $value);
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function pathToString(string|array $path): string
    {
        if (\is_string($path)) {
            return $path;
        }

        return \implode('.', \array_map(strval(...), $path));
    }

    /**
     * @return NodeManipulationInterface<array-key, mixed>
     */
    private function nodeManipulation(): NodeManipulationInterface
    {
        if (!$this->config instanceof NodeManipulationInterface) {
            throw new \RuntimeException('Config instance does not support list manipulation.');
        }

        return $this->config;
    }
}
