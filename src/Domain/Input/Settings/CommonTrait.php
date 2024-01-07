<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Input\Settings;

/**
 * @psalm-api
 */
trait CommonTrait
{
    public function category(): string
    {
        return self::CATEGORY;
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
            $this->category(),
            $this->slug()
        );
    }

    public function prop(): string
    {
        return \sprintf(
            '--wp--preset--%s--%s',
            $this->camelToUnderscore($this->category()),
            $this->camelToUnderscore($this->slug())
        );
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

    private function assertValidSlug(string $slug): void
    {
        if (
            \preg_match('#\s#', $slug)
//          || \preg_match('#[A-Z]#', $slug)
            || $slug === ''
        ) {
            throw new \InvalidArgumentException(\sprintf(
                'Slug must be lowercase, without spaces and not empty, got %s',
                $slug
            ));
        }
    }

    private function assertSlugIsWellFormed(string $slug): void
    {
        if (
            \preg_match('#\s#', $slug)
            || $slug === ''
        ) {
            throw new \Exception(\sprintf(
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
    private function camelToUnderscore(string $string, string $us = '-'): string
    {
        return strtolower((string)preg_replace(
            '#(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])#',
            $us,
            $string
        ));
    }
}