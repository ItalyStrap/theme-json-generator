<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use WP_CLI;

function test_callable(): array {
	return ['key'=>'value'];
}

WP_CLI::add_command( 'foo', '\ItalyStrap\ThemeJsonGenerator\CLI\Command' );
