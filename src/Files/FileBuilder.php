<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator\Files;

interface FileBuilder {

	/**
	 * @throws \Exception
	 */
	public function build( callable $callable ): void;
}
