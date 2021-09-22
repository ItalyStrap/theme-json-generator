<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use ItalyStrap\Config\ConfigInterface;
use ItalyStrap\ThemeJsonGenerator\FileBuilder;
use ItalyStrap\ThemeJsonGenerator\ScssFileBuilder;
use Prophecy\Prophet;

class ThemeSassGeneratorTest extends UnitTest {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	/**
	 * @var string
	 */
	private $theme_sass_path;

	// phpcs:ignore
	protected function _before() {
		$this->prophet = new Prophet;
		$this->config = $this->prophet->prophesize( ConfigInterface::class );
	}

	// phpcs:ignore
    protected function _after() {
	}

	protected function getInstance(): ScssFileBuilder {
//		$this->theme_sass_path = \codecept_output_dir(\rand() . '/theme.scss');
		$this->theme_sass_path = \codecept_output_dir('/theme.scss');
		$sut = new ScssFileBuilder( $this->theme_sass_path );
		$this->assertInstanceOf( FileBuilder::class, $sut, '' );
		$this->assertInstanceOf( ScssFileBuilder::class, $sut, '' );
		return $sut;
	}

	/**
	 * @test
	 */
	public function itShouldBeInstantiatable() {
		$sut = $this->getInstance();
	}

	/**
	 * @test
	 */
	public function itShouldCreateScssFile() {

		$sut = $this->getInstance();
		$sut->build( function () {
			return	[
				'settings' => [
					'custom'	=> [
						'alignment--center'				=> 'center',
					],
					'color'	=> [
						'palette' => [
						],
					],
				]
			];
		} );

		$this->assertFileExists( $this->theme_sass_path, '' );
		$this->assertFileIsReadable( $this->theme_sass_path, '' );
		$this->assertFileIsWritable( $this->theme_sass_path, '' );

		\unlink( $this->theme_sass_path );
	}

	public function customSettingsProvider() {

		yield 'Custom' => [
			[
				'alignmentCenter'	=> 'center',
			],
			'$alignment-center',
			'--wp-custom--alignment-center !default;',
		];

		yield 'Custom with child' => [
			[
				'alignment'	=> [
					'center'	=> 'center',
				],
			],
			'$alignment--center',
			'--wp-custom--alignment--center !default;',
		];
	}

	/**
	 * @dataProvider customSettingsProvider
	 * @test
	 */
	public function itShouldIterateCustomSettingsFor( array $settings, string $variable, string $css_prop ) {

		$sut = $this->getInstance();
		$sut->build( function () use ( $settings ) {
			return	[
				'settings' => [
					'custom'	=> $settings,
				]
			];
		} );

		$this->assertStringEqualsFile(
			$this->theme_sass_path,
			\sprintf(
				'%s: %s' . PHP_EOL,
				$variable,
				$css_prop
			),
			''
		);

		\unlink( $this->theme_sass_path );
	}

	public function presetSettingsProvider() {
		yield 'Color palette' => [
			[
				'color'	=> [
					'palette' => [
						[
							"slug" => "primary",
						]
					],
				],
			],
			'$primary',
			'--wp--preset--color--primary !default',
		];

		yield 'Color gradients' => [
			[
				'color'	=> [
					'gradients' => [
						[
							"slug" => "blush-light-purple",
						],
					],
				],
			],
			'$blush-light-purple',
			'--wp--preset--gradient--blush-light-purple !default',
		];

		yield 'Typography fontSizes' => [
			[
				'typography' => [
					'fontSizes' => [
						[
							"slug" => "normal",
						],
					],
				],
			],
			'$normal',
			'--wp--preset--font-size--normal !default',
		];

		yield 'Typography fontFamilies' => [
			[
				'typography' => [
					'fontFamilies' => [
						[
							'slug' => 'system-font',
						],
					],
				],
			],
			'$system-font',
			'--wp--preset--font-family--system-font !default',
		];
	}

	/**
	 * @test
	 * @dataProvider presetSettingsProvider
	 */
	public function itShouldIteratePresetSettingsFor( $data, $expected_slug, $expected_css_variable ) {
		$sut = $this->getInstance();

		$sut->build( function () use ( $data ) {
			$theme = [
				'settings' => $data
			];
			return	$theme;
		} );

		$this->assertStringEqualsFile(
			$this->theme_sass_path,
			\sprintf(
				'%s: %s;' . PHP_EOL,
				$expected_slug,
				$expected_css_variable
			),
			''
		);

		\unlink( $this->theme_sass_path );
	}
}
