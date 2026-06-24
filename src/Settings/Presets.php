<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

use ItalyStrap\Config\AccessValueInArrayWithNotationTrait;
use ItalyStrap\ThemeJsonGenerator\Settings;
use ItalyStrap\ThemeJsonGenerator\Settings\Custom\Custom;

/**
 * @see PresetsTest
 * @see PresetsIntegrationTest
 */
final class Presets implements PresetsInterface
{
    use AccessValueInArrayWithNotationTrait;

    /**
     * @var array<array-key, mixed>
     */
    private array $collection = [];

    /**
     * @var array<string, true>
     */
    private array $registeredPaths = [];

    public function add(PresetInterface $item): self
    {
        $key = [$item->type(), ...$this->path($item->slug())];

        $this->assertIsUnique($key);

        $this->insertValue(
            $this->collection,
            $key,
            $item
        );

        $this->registeredPaths[$this->normalizePath($key)] = true;

        return $this;
    }

    public function addToBlock(string $block, PresetInterface $item): self
    {
        $key = [Settings::BLOCKS_SECTION, $block, $item->type(), ...$this->path($item->slug())];

        $this->assertIsUnique($key);
        $this->insertValue($this->collection, $key, $item);
        $this->registeredPaths[$this->normalizePath($key)] = true;

        return $this;
    }

    public function addMultiple(array $items): self
    {
        foreach ($items as $item) {
            $this->add($item);
        }

        return $this;
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    public function get(array|string $key, $default = null)
    {
        return $this->findValue($this->collection, $this->path($key), $default);
    }

    /**
     * Just a reminder:
     * '/{{([\w.]+)}}|var:(preset|custom)\|([\w.]+)\|([\w.]+)/'
     * The pattern above will match also the shortcut syntax used as a reference by WordPress to search values.
     * For now let the WordPress do the job, and we will see later if we need to change this.
     */
    public function parse(string $content): string
    {
        $pattern = '/{{([\w.]+)}}/';
        $found = (int)\preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        if ($found === 0) {
            return $content;
        }

        $replace = [];
        $search = [];
        foreach ($matches as $match) {
            $search[] = \array_shift($match);

            /**
             * The second parameter is needed to search also in the custom collection
             * Let say you define a custom value `spacer.base`, `spacer` is not any Presets category,
             * so in this case the second $this->get() is added as default and will search in the custom collection
             * for `custom.spacer.base` and if found will return the value from the custom collection.
             * If any value in the Preset and Custom collection is found then the null default will be returned.
             *
             * @var PresetInterface|array<array-key, mixed>|null $item
             */
            $item = $this->get($match[0], $this->get(Custom::TYPE . '.' . $match[0]));

            if ($item === null) {
                throw new \RuntimeException(sprintf('{{%s}} does not exists', $match[0]));
            }

            if (!$item instanceof PresetInterface) {
                throw new \RuntimeException(sprintf(
                    '{{%s}} resolves to a preset group, not a preset.',
                    $match[0]
                ));
            }

            $replace[] = $item->var();
        }

        return \str_replace($search, $replace, $content);
    }

    /**
     * @param array<array-key, string|int>|string $key
     */
    private function assertIsUnique(array|string $key): void
    {
        $path = $this->path($key);
        $normalizedPath = $this->normalizePath($path);

        if (\array_key_exists($normalizedPath, $this->registeredPaths)) {
            throw new \RuntimeException(
                \sprintf(
                    'Preset path %s is already registered.',
                    $normalizedPath
                )
            );
        }

        $registeredParentPath = $this->registeredParentPath($path);

        if ($registeredParentPath !== null) {
            throw new \RuntimeException(
                \sprintf(
                    'Cannot register %s because %s is already a preset.',
                    $normalizedPath,
                    $registeredParentPath
                )
            );
        }

        if ($this->containsNestedPresets($normalizedPath)) {
            throw new \RuntimeException(
                \sprintf(
                    'Cannot register %s because %s already contains nested presets.',
                    $normalizedPath,
                    $normalizedPath
                )
            );
        }
    }

    /**
     * @param list<string> $path
     */
    private function registeredParentPath(array $path): ?string
    {
        for ($index = 1, $length = \count($path); $index < $length; ++$index) {
            $parentPath = $this->normalizePath(\array_slice($path, 0, $index));

            if (\array_key_exists($parentPath, $this->registeredPaths)) {
                return $parentPath;
            }
        }

        return null;
    }

    private function containsNestedPresets(string $path): bool
    {
        $prefix = $path . '.';

        foreach (\array_keys($this->registeredPaths) as $registeredPath) {
            if (\str_starts_with($registeredPath, $prefix)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<array-key, string|int>|string $path
     */
    private function normalizePath(array|string $path): string
    {
        if (\is_string($path)) {
            return $path;
        }

        return \implode('.', \array_map(strval(...), $path));
    }

    /**
     * @param array<array-key, string|int>|string $path
     * @return list<string>
     */
    private function path(array|string $path): array
    {
        if (\is_string($path)) {
            return \explode('.', $path);
        }

        return \array_map(strval(...), \array_values($path));
    }

    /**
     * @return array<array-key, mixed>
     */
    public function collection(): array
    {
        return $this->collection;
    }
}
