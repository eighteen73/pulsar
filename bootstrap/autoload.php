<?php
/**
 * Autoload bootstrap file.
 *
 * This file is used to autoload classes and functions necessary for the theme
 * to run. Classes utilize the PSR-4 autoloader in Composer and is defined in
 * `composer.json`.
 *
 * @package Pulsar
 */

namespace Pulsar;

/**
 * Run the Composer autoloader.
 *
 * Auto-load any projects via the Composer autoloader. Be sure to check if the
 * file exists in case someone's using Composer to load their dependencies in
 * a different directory. This also autoloads our theme's classes.
 */
if ( file_exists( get_parent_theme_file_path( 'vendor/autoload.php' ) ) ) {
	require_once get_parent_theme_file_path( 'vendor/autoload.php' );
} else {
	spl_autoload_register(
		function( $class_name ) {
			$namespace = 'Pulsar';

			if ( strpos( $class_name, $namespace . '\\' ) !== 0 ) {
				return false;
			}

			$parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );

			$path = get_template_directory() . '/app';
			foreach ( $parts as $part ) {
				$path .= '/' . $part;
			}
			$path .= '.php';

			if ( ! file_exists( $path ) ) {
				return false;
			}

			require_once $path;

			return true;
		}
	);
}
