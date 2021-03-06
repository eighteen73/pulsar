<?php
/**
 * Laravel Mix asset path management.
 *
 * @package Pulsar
 */

namespace Pulsar\Tools;

/**
 * Mix class.
 */
class Mix {

	/**
	 * @var array
	 */
	protected static $mix = [];

	protected static function build_mode() {
		return in_array( wp_get_environment_type(), [ 'development', 'local' ], true ) ? 'dev' : 'live';
	}

	/**
	 * Return the mix manifest.
	 */
	protected static function mix() {

		if ( self::$mix ) {
			return self::$mix;
		}

		$build_mode = self::build_mode();
		$file       = get_parent_theme_file_path( "dist/mix/{$build_mode}/mix-manifest.json" );
		self::$mix  = (array) json_decode( file_get_contents( $file ), true );

		if ( is_child_theme() ) {
			$child = get_theme_file_path() . 'dist/mix-manifest.json';

			if ( file_exists( $child ) ) {
				self::$mix = array_merge(
					self::$mix,
					(array) json_decode( file_get_contents( $file ), true )
				);
			}
		}

		return self::$mix;
	}

	/**
	 * Helper function for outputting an asset URL in the theme. This integrates
	 * with Laravel Mix for handling cache busting. If used when you enqueue a script
	 * or style, it'll append an ID to the filename.
	 *
	 * @param  string $path A relative path/file to append to the `public` folder.
	 * @return string
	 */
	public static function asset( $path ) {

		// Get the Laravel Mix manifest.
		$manifest = self::mix();

		// Make sure to trim any slashes from the front of the path.
		$path = '/' . ltrim( $path, '/' );

		if ( $manifest && isset( $manifest[ $path ] ) ) {

			// Use the dev build in development environments
			$build_mode = self::build_mode();
			$base_uri   = get_theme_file_uri( "dist/mix/{$build_mode}" );

			return $base_uri . $manifest[ $path ];
		}

		return null;
	}
}
