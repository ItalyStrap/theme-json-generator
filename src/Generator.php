<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

class Generator {

	/**
	 * @var array<string>
	 */
	private $config;

	/**
	 * ThemeJsonGenerator constructor.
	 * @param array<string> $config
	 */
	public function __construct( array $config ) {
		$this->config = $config;
	}

	public function toJson(): string {
		return (string) \json_encode( $this->config );
	}

	public function generate() {
//		$theme_json = \codecept_data_dir('fixtures/theme.json');
		$theme_json = 'tests/_data/fixtures/theme.json';
		$file = new \SplFileObject( $theme_json, 'w' );
		return $file->fwrite( (string) json_encode( $this->config, JSON_PRETTY_PRINT ) );
	}
}
