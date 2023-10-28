<?php

declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Application\Service;

use Composer\Composer as BaseComposer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use ItalyStrap\ThemeJsonGenerator\Files\JsonFileBuilder;
use ItalyStrap\ThemeJsonGenerator\Files\ScssFileBuilder;

use function array_replace_recursive;
use function dirname;
use function is_callable;

class ThemeJsonGenerator
{
	public const TYPE_THEME = 'wordpress-theme';
	public const THEME_JSON_KEY = 'theme-json';
	public const CALLBACK = 'callable';

	public function __invoke(BaseComposer $composer, IOInterface $io): void
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

		/** @var array<string, mixed> $theme_json_config */
		$theme_json_config = $composer_extra[self::THEME_JSON_KEY];

		if (!is_callable($theme_json_config[self::CALLBACK])) {
			throw new \RuntimeException(\sprintf(
				'Maybe the %s is not a valid callable',
				$theme_json_config[self::CALLBACK]
			));
		}

		try {
			( new JsonFileBuilder($path . '/theme.json') )
				->build($theme_json_config[self::CALLBACK]);

			$path_for_theme_sass = $path . '/' . $theme_json_config[ 'path-for-theme-sass' ];
			if (\is_writable($path_for_theme_sass)) {
				( new ScssFileBuilder(
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
