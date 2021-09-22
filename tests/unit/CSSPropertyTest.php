<?php
declare(strict_types=1);

namespace ItalyStrap\Tests\Unit;

use Codeception\Test\Unit;
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

	/**
	 * @test
	 */
	public function testSomeFeature() {
//		$custom = $this->input_data[ SectionNames::SETTINGS ];

		$custom = [
			'alignment' => [
				'center' => 'center',
				'alignedMaxWidth' => '50%',
				'global' => [
					'left' => 'left',
				],
			]
		];

		$result = $this->flattenTree( $custom );

		/**
		 * $result = [
		 * 	'--wp--custom--color--white' => '--wp--custom--color--white'
		 * ];
		 */
		codecept_debug( $result );

		$this->assertArrayHasKey('alignment--center', $result, '');
		$this->assertArrayHasKey('alignment--aligned-max-width', $result, '');

		$this->assertStringContainsString('center', $result['alignment--center'], '');
		$this->assertStringContainsString('50%', $result['alignment--aligned-max-width'], '');

		$result = $this->flattenTree( $custom, '--wp--custom--' );
		$this->assertStringContainsString('center', $result['--wp--custom--alignment--center'], '');


		$custom = [
			'alignment' => [
				'global' => [
					'left'
				],
			]
		];

		$this->expectException('\Throwable');
		$this->expectException('\RuntimeException');
		$result = $this->flattenTree( $custom, '--wp--custom--' );
	}

	/**
	 * @author \WP_Theme_Json::flatten_tree
	 * @param array $tree
	 * @param string $prefix
	 * @param string $token
	 * @return array
	 */
	private function flattenTree( array $tree, $prefix = '', $token = '--' ): array {
		$result = [];
		foreach ( $tree as $property => $value ) {
			if ( ! \is_string( $property ) ) {
				throw new \RuntimeException(
					\sprintf(
						'Property key is not a string, actual value is: %s',
						(string) $property
					)
				);
			}

			$new_key = $prefix . str_replace(
				'/',
				'-',
				\strtolower( \preg_replace( '/(?<!^)[A-Z]/', '-$0', $property ) ) // CamelCase to kebab-case.
			);

			if ( is_array( $value ) ) {
				$new_prefix = $new_key . $token;
				$result     = array_merge(
					$result,
					$this->flattenTree( $value, $new_prefix, $token )
				);
				continue;
			}

			$result[ $new_key ] = $value;
		}

		return $result;
	}
}
