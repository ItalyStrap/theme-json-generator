<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem;

use ItalyStrap\Config\Config;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Helper\ConvertCase;

class ScssFileWriter implements FileBuilder
{
    use ConvertCase;

    private string $path;

    /**
     * @psalm-suppress TooManyTemplateParams
     * @var ConfigInterface<mixed>|null
     */
    private $config;

    /**
     * @psalm-suppress TooManyTemplateParams
     * @param string $path
     * @param ConfigInterface<mixed>|null $config
     */
    public function __construct(string $path, ConfigInterface $config = null)
    {
        $this->path = $path;
        $this->config = $config ?? new Config();
    }

    /**
     * @inheritDoc
     */
    public function build(callable $callable): void
    {
        if (\file_exists($this->path) && \is_file($this->path)) {
            \unlink($this->path);
        }

        $_file = new \SplFileObject($this->path, 'a');

        if (! $_file->isWritable()) {
            throw new \RuntimeException('The file is not writable');
        }

        /**
         * @var array<string|int, mixed>
         */
        $data = $callable();

        $_file->fwrite($this->generateScssContent($data));
        $_file = null;
    }

    /**
     * @param array<string|int, mixed> $data
     * @return string
     */
    private function generateScssContent(array $data): string
    {
        if ($data === []) {
            return '// No data are provided!';
        }

        /**
         * @psalm-suppress PossiblyNullReference
         */
        $this->config->merge($data); /** @phpstan-ignore-line */

        $content = '';

        $schema = [
            'settings.color.palette'            => '--wp--preset--color',
            'settings.color.gradients'          => '--wp--preset--gradient',
            'settings.typography.fontFamilies'  => '--wp--preset--font-family',
            'settings.typography.fontSizes'     => '--wp--preset--font-size',
        ];

        foreach ($schema as $slug => $prefix) {
            /** @var array<string, string> $item */
            foreach ((array) $this->config->get($slug) as $item) {
                $content .= $this->generateScssVariableAndCssVariable($item['slug'], $prefix);
            }
        }

        /** @var array<string|int, string> $custom */
        $custom = (array) $this->config->get('settings.custom');
        $custom = $this->flattenTree($custom);

//      $map = '$wp-custom: (' . PHP_EOL;
        foreach ($custom as $property_name => $_value) {
            $content .= $this->generateScssVariableAndCssVariable($property_name, '--wp--custom');
//          $map .= $this->generateScssMap( $property_name, '--wp-custom' );
        }
//      $map .= ');' . PHP_EOL;

//      return $content . $map;
        return $content;
    }

//  private function generateScssMap( string $slug, string $prefix ): string {
//      return \sprintf(
//          '"%1$s": %2$s--%1$s,' . PHP_EOL,
//          $this->camelToUnderscore( $slug ),
//          $prefix
//      );
//  }

    /**
     * @param string $slug
     * @param string $prefix
     * @return string
     */
    private function generateScssVariableAndCssVariable(string $slug, string $prefix): string
    {
        return \sprintf(
            '$%3$s--%1$s: %2$s--%1$s;' . PHP_EOL,
            $this->camelToUnderscore($slug),
            $prefix,
            \ltrim($prefix, '-')
        );
    }

    /**
     * @param array<string|int, string> $tree
     * @param string $prefix
     * @param string $token
     * @return array<string, string>
     * @author \WP_Theme_Json::flatten_tree
     */
    private function flattenTree(array $tree, string $prefix = '', string $token = '--'): array
    {
        $result = [];

        /**
         * @var string|array<string, string> $value
         */
        foreach ($tree as $property => $value) {
            if (! \is_string($property)) {
                throw new \RuntimeException(
                    \sprintf(
                        'Property key is not a string, actual value is: %s',
                        (string) $property
                    )
                );
            }

            $new_key = $prefix . \str_replace(
                '/',
                '-',
                $this->camelToUnderscore($property)
            );

            if (\is_array($value)) {
                $new_prefix = $new_key . $token;
                $result     = \array_merge(
                    $result,
                    $this->flattenTree($value, $new_prefix, $token)
                );
                continue;
            }

            $result[ $new_key ] = $value;
        }

        return $result;
    }
}
