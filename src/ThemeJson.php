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

final readonly class ThemeJson implements \JsonSerializable
{
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

    #[ThemeSchemaCoverage(['topLevel', self::SCHEMA])]
    public function schema(string $schema): self
    {
        return $this->setRoot(self::SCHEMA, $schema);
    }

    #[ThemeSchemaCoverage(['topLevel', self::VERSION])]
    public function version(int $version): self
    {
        return $this->setRoot(self::VERSION, $version);
    }

    #[ThemeSchemaCoverage(['topLevel', self::TITLE])]
    public function title(string $title): self
    {
        return $this->setRoot(self::TITLE, $title);
    }

    #[ThemeSchemaCoverage(['topLevel', self::SLUG])]
    public function slug(string $slug): self
    {
        return $this->setRoot(self::SLUG, $slug);
    }

    #[ThemeSchemaCoverage(['topLevel', self::DESCRIPTION])]
    public function description(string $description): self
    {
        return $this->setRoot(self::DESCRIPTION, $description);
    }

    #[ThemeSchemaCoverage(['topLevel', Settings::SECTION])]
    public function settings(): Settings
    {
        return new Settings($this->presets, new SettingsContext($this, [Settings::SECTION]));
    }

    #[ThemeSchemaCoverage(['topLevel', Styles::SECTION])]
    public function styles(): Styles
    {
        return new Styles(
            $this->presets,
            new StyleContext($this, [Styles::SECTION]),
            new Css($this->presets),
            new Scss(new Css($this->presets), new Compiler(), $this->presets),
        );
    }

    #[ThemeSchemaCoverage(['topLevel', BlockTypes::SECTION])]
    public function blockTypes(): BlockTypes
    {
        return new BlockTypes($this);
    }

    #[ThemeSchemaCoverage(['topLevel', CustomTemplates::SECTION])]
    public function customTemplates(): CustomTemplates
    {
        return new CustomTemplates($this);
    }

    #[ThemeSchemaCoverage(['topLevel', TemplateParts::SECTION])]
    public function templateParts(): TemplateParts
    {
        return new TemplateParts($this);
    }

    #[ThemeSchemaCoverage(['topLevel', Patterns::SECTION])]
    public function patterns(): Patterns
    {
        return new Patterns($this);
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    public function set(string|array $key, mixed $value): bool
    {
        /** @phpstan-ignore-next-line Config accepts array paths, StoreInterface only advertises string keys. */
        return $this->config->set($key, $value);
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
    public function appendTo(string|array $key, mixed $value): bool
    {
        return $this->nodeManipulation()->appendTo($key, $value);
    }

    /**
     * @param array<array-key, mixed> ...$arrays
     */
    public function merge(array ...$arrays): void
    {
        $this->config->merge(...$arrays);
    }

    /**
     * @TODO Let see if we can remove this method.
     * @return ConfigInterface<array-key, mixed>
     */
    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function jsonSerialize(): mixed
    {
        return $this->config->toArray();
    }

    private function setRoot(string $key, mixed $value): self
    {
        if (!$this->set($key, $value)) {
            throw new \RuntimeException(\sprintf('Unable to set root property "%s".', $key));
        }

        return $this;
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
