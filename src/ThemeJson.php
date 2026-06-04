<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\Config\NodeManipulationInterface;
use ItalyStrap\ThemeJsonGenerator\Settings\PresetsInterface;

final readonly class ThemeJson implements \JsonSerializable
{
    /**
     * @param ConfigInterface<array-key, mixed> $config
     */
    public function __construct(
        private ConfigInterface $config,
        private PresetsInterface $presets,
    ) {
    }

    public function schema(string $schema): self
    {
        return $this->setRoot('$schema', $schema);
    }

    public function version(int $version): self
    {
        return $this->setRoot('version', $version);
    }

    public function title(string $title): self
    {
        return $this->setRoot('title', $title);
    }

    public function slug(string $slug): self
    {
        return $this->setRoot('slug', $slug);
    }

    public function description(string $description): self
    {
        return $this->setRoot('description', $description);
    }

    public function settings(): Settings
    {
        return new Settings($this, $this->presets);
    }

    public function styles(): Styles
    {
        return new Styles($this, $this->presets);
    }

    public function blockTypes(): BlockTypes
    {
        return new BlockTypes($this);
    }

    public function customTemplates(): CustomTemplates
    {
        return new CustomTemplates($this);
    }

    public function templateParts(): TemplateParts
    {
        return new TemplateParts($this);
    }

    public function patterns(): Patterns
    {
        return new Patterns($this);
    }

    /**
     * @deprecated Use styles()->set() instead.
     */
    public function setGlobalStyle(string $elementName, \JsonSerializable $config): bool
    {
        return $this->styles()->set($elementName, $config);
    }

    /**
     * @param array<string, mixed> $config
     * @deprecated Use styles()->set(['elements', $elementName], $config) instead.
     */
    public function setElementStyle(string $elementName, array $config): bool
    {
        return $this->styles()->set(['elements', $elementName], $config);
    }

    /**
     * @param array<string, mixed> $config
     * @deprecated Use settings()->blocks($blockName)->set([], $config) instead.
     */
    public function setBlockSettings(string $blockName, array $config): bool
    {
        return $this->settings()->blocks($blockName)->set([], $config);
    }

    /**
     * @param array<string, mixed> $config
     * @deprecated Use styles()->set(['blocks', $blockName], $config) instead.
     */
    public function setBlockStyle(string $blockName, array $config): bool
    {
        return $this->styles()->set(['blocks', $blockName], $config);
    }

    /**
     * @deprecated Use styles()->set(['blocks', $blockName, 'css'], $css) instead.
     */
    public function setPerBlockCss(string $blockName, string $css): bool
    {
        return $this->styles()->blocks($blockName)->set('css', $css);
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    public function set(string|array $key, mixed $value): bool
    {
        return $this->config->set($this->normalizePath($key), $value);
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    public function get(string|array $key, mixed $default = null): mixed
    {
        return $this->config->get($this->normalizePath($key), $default);
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    public function appendTo(string|array $key, mixed $value): bool
    {
        return $this->nodeManipulation()->appendTo($this->normalizePath($key), $value);
    }

    /**
     * @param array<array-key, mixed> ...$arrays
     */
    public function merge(array ...$arrays): void
    {
        $this->config->merge(...$arrays);
    }

    public function count(): int
    {
        return $this->config->count();
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

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function normalizePath(string|array $path): string
    {
        if (\is_string($path)) {
            return $path;
        }

        return \implode('.', \array_map(strval(...), $path));
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
