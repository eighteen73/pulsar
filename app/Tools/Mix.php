<?php
/**
 * Laravel Mix asset path management.
 *
 * @package NewTheme
 */

namespace NewTheme\Tools;

/**
 * Mix class.
 */
class Mix {

	/**
	 * @var array
	 */
	protected static $mix = [];

	/**
	 * Return the mix manifest.
	 */
	protected static function mix() {

		if ( self::$mix ) {
			return self::$mix;
		}

		$file      = get_parent_theme_file_path( 'public/mix-manifest.json' );
		self::$mix = (array) json_decode( file_get_contents( $file ), true );

		if ( is_child_theme() ) {
			$child = get_theme_file_path() . 'public/mix-manifest.json';

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
			$path = $manifest[ $path ];
		}

		return get_theme_file_uri( 'public' . $path );
	}
}
