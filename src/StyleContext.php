<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

final readonly class StyleContext
{
    use ThemeJsonContextTrait;

    private const ROOT_STRUCTURE = 'root';

    private const STATE_STRUCTURE = 'state';

    /**
     * @var array<string, list<string>>
     */
    private const ALLOWED_TRANSITIONS = [
        self::ROOT_STRUCTURE => [
            Styles::BLOCKS_SECTION,
            Styles::ELEMENTS_SECTION,
            Styles::VARIATIONS_SECTION,
        ],
        Styles::BLOCKS_SECTION => [
            Styles::ELEMENTS_SECTION,
            Styles::VARIATIONS_SECTION,
            self::STATE_STRUCTURE,
        ],
        Styles::ELEMENTS_SECTION => [self::STATE_STRUCTURE],
        Styles::VARIATIONS_SECTION => [
            Styles::BLOCKS_SECTION,
            Styles::ELEMENTS_SECTION,
        ],
        self::STATE_STRUCTURE => [],
    ];

    /**
     * @param list<string|int> $path
     * @param list<string> $structure
     */
    public function __construct(
        private ThemeJson $themeJson,
        private array $path,
        private array $structure = [],
    ) {
    }

    public function at(string|int ...$segments): self
    {
        /** @var list<string|int> $path */
        $path = \array_values([...$this->path, ...$segments]);

        return new self($this->themeJson, $path, $this->structure);
    }

    public function blocks(string $path): self
    {
        return $this->enter(Styles::BLOCKS_SECTION, $path);
    }

    public function elements(string $path): self
    {
        return $this->enter(Styles::ELEMENTS_SECTION, $path);
    }

    public function variations(string $variation): self
    {
        return $this->enter(Styles::VARIATIONS_SECTION, $variation);
    }

    public function state(string $state): self
    {
        if ($this->structure === []) {
            throw new \LogicException('Cannot call "state()" outside a block or element context.');
        }

        return $this->enter(self::STATE_STRUCTURE, $state, false);
    }

    /**
     * @internal
     */
    public function isScoped(): bool
    {
        return $this->structure !== [];
    }

    private function enter(string $structure, string $path, bool $includeStructureInPath = true): self
    {
        $parent = $this->structure === []
            ? self::ROOT_STRUCTURE
            : $this->structure[\array_key_last($this->structure)];

        if (!\in_array($structure, self::ALLOWED_TRANSITIONS[$parent], true)) {
            throw new \LogicException(\sprintf(
                'Cannot chain "%s()" after "%s()": this style structure is not supported. %s',
                $structure,
                $parent,
                $this->callerLocation(),
            ));
        }

        return new self(
            $this->themeJson,
            [
                ...$this->path,
                ...($includeStructureInPath ? [$structure] : []),
                $path,
            ],
            [...$this->structure, $structure],
        );
    }
}
