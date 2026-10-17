<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Settings;

trait PresetTrait
{
    public function type(): string
    {
        return self::TYPE;
    }

    public function slug(): string
    {
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
        if ($slug === '') {
            throw new \InvalidArgumentException('Preset slug must not be empty.');
        }

        if (\preg_match('/^[A-Za-z0-9-]+$/', $slug) !== 1) {
            throw new \InvalidArgumentException(\sprintf(
                'Invalid preset slug "%s": only ASCII letters, digits, and hyphens are allowed.',
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
