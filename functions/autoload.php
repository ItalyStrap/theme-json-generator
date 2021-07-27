<?php

function foo_command( $args ) {
	\WP_CLI::success( "The script has run!" );
//	\WP_CLI::success( $args[0] );
//	\WP_CLI::success( $args[1] );

	$generator = new  \ItalyStrap\ThemeJsonGenerator\JsonFileBuilder(['ciao'=>'bello']);
	$is_generated = $generator->generate();

	\WP_CLI::success( $is_generated );
}
\WP_CLI::add_command( 'foo', 'foo_command' );
