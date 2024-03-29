<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\WPCLI;

use Exception;
use ItalyStrap\Config\Config;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;
use WP_CLI;

use function array_replace_recursive;
use function get_stylesheet_directory;
use function get_template_directory;
use function is_callable;
use function is_child_theme;
use function sprintf;

/**
 * @psalm-api
 */
final class Dump
{
    /**
     * @var string
     */
    public const NAME = 'theme-json dump';

    /**
     * @param array<string, mixed> $args
     * @param array<string, mixed> $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        throw new Exception('This command is not implemented yet');

        /**
         * --parent
         * : Argument to generate theme.json also for parent theme
         * @var array<string, mixed> $assoc_args_default
         */
        $assoc_args_default = [
            'parent' => false,
        ];

        $assoc_args = array_replace_recursive($assoc_args_default, $assoc_args);

        /**
         * @var array<string, string|int> $extra_config
         */
        $extra_config = WP_CLI::get_runner()->extra_config;

        $callable = \array_key_exists('THEME_JSON_CALLABLE', $extra_config)
            ? $extra_config['THEME_JSON_CALLABLE']
            : '';

        if (!is_callable($callable)) {
            WP_CLI::line(\sprintf(
                "No valid callable provided, got '%s'",
                $callable
            ));
            return;
        }

        $theme_json_path = [];
        $theme_json_path[] = get_stylesheet_directory() . '/theme.json';

        if (is_child_theme() && $assoc_args['parent']) {
            $theme_json_path[] = get_template_directory() . '/theme.json';
        }

        foreach ($theme_json_path as $path) {
            $this->loopsThemePathAndGenerateFile($path, $callable);
        }
    }

    /**
     * @param string $path
     * @param callable $callable
     */
    private function loopsThemePathAndGenerateFile(string $path, callable $callback): void
    {
        try {
            $result = (array)$callback();
            $data = new Config($result);
            (new JsonFileWriter($path))->write($data);
            WP_CLI::success(sprintf(
                '%s was generated!',
                $path
            ));
        } catch (Exception $exception) {
            WP_CLI::line($exception->getMessage());
        }
    }
}
