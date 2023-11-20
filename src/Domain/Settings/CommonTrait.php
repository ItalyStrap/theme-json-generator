<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Domain\Settings;

trait CommonTrait
{
    public function slug(): string
    {
        if (\preg_match('/\s/', $this->slug)) {
            throw new \Exception(\sprintf(
                'Slug with spaces is not allowed, got %s',
                $this->slug
            ));
        }

        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function category(): string
    {
        return self::CATEGORY;
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
            empty($fallback) ? '' : ',' . $fallback
        );
    }

    private function isValidSlug(string $slug): void
    {
        if (\preg_match('/\s/', $slug) || \preg_match('/[A-Z]/', $slug)) {
            throw new \Exception('Slug must be lowercase and without spaces');
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
            '/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/',
            $us,
            $string
        ));
    }
}
