<?php
declare(strict_types=1);

namespace ItalyStrap\ThemeJsonGenerator;

use WP_CLI;

if ( !class_exists('WP_CLI') ) {
	return;
}

function test_callable( string $path ): array {
	return ['key'=>'value'];
}

WP_CLI::add_command( 'theme-json generate', '\ItalyStrap\ThemeJsonGenerator\Appkication\Commands\WPCLI' );
