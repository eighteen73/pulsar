<?php
/**
 * Config Class.
 *
 * A simple class for grabbing and returning a configuration file from `/config`.
 *
 * @package Pulsar
 */

namespace Pulsar\Tools;

/**
 * Config class.
 */
class Config {

	/**
	 * Includes and returns a given PHP config file. The file must return
	 * an array.
	 *
	 * @param  string $name The name of the config file.
	 * @return array
	 */
	public static function get( $name ) {

		$file = static::path( "{$name}.php" );

		return (array) apply_filters(
			"pulsar/config/{$name}/",
			file_exists( $file ) ? include( $file ) : []
		);
	}

	/**
	 * Returns the config path or file path if set.
	 *
	 * @param  string $file The file name.
	 * @return string
	 */
	public static function path( $file = '' ) {

		$file = trim( $file, '/' );

		return get_theme_file_path( $file ? "config/{$file}" : 'config' );
	}
}
