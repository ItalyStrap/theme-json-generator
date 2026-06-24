<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

final readonly class SettingsContext
{
    use ThemeJsonContextTrait {
        path as public;
    }

    /**
     * @param list<string|int> $path
     */
    public function __construct(
        private ThemeJson $themeJson,
        private array $path,
        private bool $insideBlock = false,
    ) {
    }

    public function blocks(string $block): self
    {
        if ($this->insideBlock) {
            throw new \LogicException(\sprintf(
                'Cannot chain "blocks()" after "blocks()": this settings structure is not supported. %s',
                $this->callerLocation(),
            ));
        }

        if (\preg_match('/^[a-z][a-z0-9-]*\/[a-z][a-z0-9-]*$/', $block) !== 1) {
            throw new \InvalidArgumentException(\sprintf(
                'Expected a valid block name, got "%s".',
                $block
            ));
        }

        return new self($this->themeJson, [...$this->path, Settings::BLOCKS_SECTION, $block], true);
    }

    public function blockName(): ?string
    {
        if (!$this->insideBlock) {
            return null;
        }

        $blocksIndex = \array_search(Settings::BLOCKS_SECTION, $this->path, true);
        if ($blocksIndex === false) {
            return null;
        }

        $block = $this->path[$blocksIndex + 1] ?? null;
        return \is_string($block) ? $block : null;
    }
}
