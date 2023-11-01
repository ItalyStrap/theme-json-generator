<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Commands\Composer;

use Composer\Command\BaseCommand;
use Composer\Composer as BaseComposer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\JsonFileWriter;
use ItalyStrap\ThemeJsonGenerator\Infrastructure\Filesystem\ScssFileWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-api
 */
final class ThemeJson extends BaseCommand
{
    public const NAME = 'theme-json';

    protected static $defaultName = 'theme-json';
    protected static $defaultDescription = 'Generate theme.json file';
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    protected function configure(): void
    {
        $this->setHelp('This command generate theme.json file');

        $this->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            \sprintf(
                'If set, %s will run in dry run mode.',
                self::NAME
            )
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start generating theme.json file');
        $io = $this->getIO();
        $composer = $this->requireComposer();

        // Get the value of --dry-run
        $dry_run = $input->getOption('dry-run');

        $this->process($composer, $io);

        $output->writeln('End generating theme.json file');

        return Command::SUCCESS;
    }

    public const TYPE_THEME = 'wordpress-theme';
    public const THEME_JSON_KEY = 'theme-json';
    public const CALLBACK = 'callable';

    private function process(BaseComposer $composer, IOInterface $io): void
    {
        $rootPackage = $composer->getPackage();

        /** @var string $vendorPath */
        $vendorPath = $composer->getConfig()->get('vendor-dir');
        $rootPackagePath = dirname($vendorPath);

        if ($rootPackage->getType() === self::TYPE_THEME) {
            $this->writeFile($rootPackage, $rootPackagePath, $io);
            return;
        }

        $repo = $composer->getRepositoryManager();
        foreach ($rootPackage->getRequires() as $link) {
            $constraint = $link->getConstraint();
            $package = $repo->findPackage($link->getTarget(), $constraint);
            $packagePath = $vendorPath . '/' . $link->getTarget();
            if ($package && $package->getType() === self::TYPE_THEME) {
                $this->writeFile($package, $packagePath, $io);
            }
        }
    }

    private function writeFile(PackageInterface $package, string $path, IOInterface $io): void
    {
        $composer_extra = array_replace_recursive($this->getDefaultExtra(), $package->getExtra());
        $this->config->merge($package->getExtra()[self::THEME_JSON_KEY] ?? []);

        /** @var array<string, mixed> $theme_json_config */
        $theme_json_config = $composer_extra[self::THEME_JSON_KEY];

        if (!is_callable($theme_json_config[self::CALLBACK])) {
            throw new \RuntimeException(\sprintf(
                'Maybe the %s is not a valid callable',
                $theme_json_config[self::CALLBACK]
            ));
        }

        try {
            ( new JsonFileWriter($path . '/theme.json') )
                ->build($theme_json_config[self::CALLBACK]);

            $path_for_theme_sass = $path . '/' . $theme_json_config[ 'path-for-theme-sass' ];
            if (\is_writable($path_for_theme_sass)) {
                ( new ScssFileWriter(
                    \rtrim($path_for_theme_sass, '/') . '/theme.scss'
                ) )->build($theme_json_config[self::CALLBACK]);
            }
        } catch (\Exception $e) {
            $io->write($e->getMessage());
        }
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function getDefaultExtra(): array
    {
        return [
            'theme-json' => [
                self::CALLBACK => false,
                'path-for-theme-sass'   => '',
            ],
        ];
    }
}
