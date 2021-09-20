<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use ItalyStrap\ThemeJsonGenerator\ScssFileBuilder;

// phpcs:ignore
include_once 'UnitTest.php';

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
	}

	// phpcs:ignore
    protected function _after() {
	}

	protected function getInstance(): ScssFileBuilder {
//		$this->theme_sass_path = \codecept_output_dir(\rand() . '/theme.scss');
		$this->theme_sass_path = \codecept_output_dir('/theme.scss');
		$sut = new ScssFileBuilder( $this->theme_sass_path );
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

	/**
	 * @test
	 */
	public function itShouldIterateTheFileScssCreation() {

		$sut = $this->getInstance();
		$sut->build( function () {
			return	[
				'settings' => [
					'custom'	=> [
						'alignment--center'				=> 'center',
						'alignment--aligned-max-width'	=> '50%',
						'alignment--global--left'		=> 'left',
					],
					'color'	=> [
						'palette' => [
						],
					],
				]
			];
		} );

		$file = \file_get_contents($this->theme_sass_path);

		$this->assertStringContainsString(
			'$alignment--global--left',
			$file
		);

		$this->assertStringContainsString(
			'--wp-custom--alignment--center',
			$file
		);

		\unlink( $this->theme_sass_path );
	}

	public function schemaProvider() {
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
			'primary',
			'--wp--preset--color--primary',
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
			'blush-light-purple',
			'--wp--preset--gradient--blush-light-purple',
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
			'normal',
			'--wp--preset--font-size--normal',
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
			'system-font',
			'--wp--preset--font-family--system-font',
		];

		/**
		 * @todo Test sbagliato, la custom property non è corretta
		 */
		yield 'Custom variables' => [
			[
				'custom'	=> [
					'alignment' => [
						'center'	=> 'center',
					]
				],
			],
			'alignment',
			'--wp-custom--alignment',
		];
	}

	/**
	 * @test
	 * @dataProvider schemaProvider
	 */
	public function itShouldCreateScssFileFromPresetAndCustomFor( $data, $expected_slug, $expected_css_variable ) {
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
				'$%s: %s;' . PHP_EOL,
				$expected_slug,
				$expected_css_variable
			),
			''
		);

		\unlink( $this->theme_sass_path );
	}
}
