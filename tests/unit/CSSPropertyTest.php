<?php
declare(strict_types=1);

namespace ItalyStrap\Tests;

use Codeception\Test\Unit;
use ItalyStrap\ThemeJsonGenerator\SectionNames;
use UnitTester;
use function codecept_data_dir;

class CSSPropertyTest extends Unit {

	/**
	 * @var UnitTester
	 */
	protected $tester;

	/**
	 * @var mixed
	 */
	private $input_data;

	// phpcs:ignore
	protected function _before() {
		$this->input_data = require codecept_data_dir('fixtures/input-data.php');
	}

	// phpcs:ignore
	protected function _after() {
	}

	// tests
	public function testSomeFeature() {
//		$palette = $this->input_data[ SectionNames::SETTINGS ]['color']['palette'];
//
//		foreach ($palette as $value) {
//			codecept_debug( $value );
//
//			codecept_debug( '--wp--preset--color--' . $value['slug'] );
//		}
//
//		$palette = $this->input_data[ SectionNames::SETTINGS ]['custom']['color'];
//
//		foreach ($palette as $key => $value) {
//			codecept_debug( $key );
//
//			codecept_debug( '--wp--custom--color--' . $key );
//		}
//
//		codecept_debug( __DIR__ );

//		require_once __DIR__ . '/../../../../../wp-includes/class-wp-block-type-registry.php';
//		require_once __DIR__ . '/../../../../../wp-includes/class-wp-theme-json.php';
//		require_once __DIR__ . '/../../../../../wp-includes/functions.php';
//
//		$theme_json = new \WP_Theme_JSON( $this->input_data );
	}
}
