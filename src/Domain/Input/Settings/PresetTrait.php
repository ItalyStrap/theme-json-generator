<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings;

/**
 * @psalm-api
 */
trait PresetTrait
{
    public function type(): string
    {
        return self::TYPE;
    }

    public function slug(): string
    {
        $this->assertSlugIsWellFormed($this->slug);
        return $this->slug;
    }

    public function ref(): string
    {
        return \sprintf(
            '{{%s.%s}}',
            $this->type(),
            $this->slug()
        );
    }

    public function prop(): string
    {
        return $this->camelToSnake(\sprintf(
            '--wp--preset--%s--%s',
            $this->type(),
            $this->slug()
        ));
    }

    public function var(string $fallback = ''): string
    {
        return \sprintf(
            'var(%s%s)',
            $this->prop(),
            $fallback === '' ? '' : ',' . $fallback
        );
    }

    public function __toString(): string
    {
        return $this->var();
    }

    private function assertSlugIsWellFormed(string $slug): void
    {
        if (
            \preg_match('#\s#', $slug)
            || $slug === ''
        ) {
            throw new \InvalidArgumentException(\sprintf(
                'Slug with spaces is not allowed, got %s',
                $slug
            ));
        }
    }

    /**
     * @link https://stackoverflow.com/a/40514305/7486194
     * @param string $string
     * @param string $us
     * @return string
     */
    private function camelToSnake(string $string, string $us = '-'): string
    {
        return strtolower((string)preg_replace(
            '#(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])#',
            $us,
            $string
        ));
    }
}
