<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Composer\Json\JsonFile;

class JsonFileBuilder {

	/**
	 * @var array<string>
	 */
	private $path;

	/**
	 * ThemeJsonGenerator constructor.
	 * @param string $path
	 */
	public function __construct( string $path ) {
		$this->path = $path;
	}

	/**
	 * @throws \Exception
	 */
	public function build( callable $callable ) {
		$json_file = new ComposerJsonFileAdapter( new JsonFile( $this->path ) );
		$json_file->write( $callable( $this->path ) );
	}
}
