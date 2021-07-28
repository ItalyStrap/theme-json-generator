<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use WP_CLI;

function test_callable( string $path ): array {
	return ['key'=>'value'];
}

WP_CLI::add_command( 'theme json', '\ItalyStrap\ThemeJsonGenerator\CLI\Command' );
