<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use Composer\Json\JsonFile;

final class ComposerFileJsonAdapter
{
	/**
	 * @var JsonFile
	 */
	private $jsonFile;

	/**
	 * FileJsonAdapter constructor.
	 * @param JsonFile $jsonFile
	 */
	public function __construct( JsonFile $jsonFile ) {
		$this->jsonFile = $jsonFile;
	}

	/**
	 * @throws \Exception
	 */
	public function write( array $input_data, $options = null ) {
		$this->jsonFile->write( $input_data, $options );
	}
}
