<?php
/**
 * Asset management.
 *
 * @package Pulsar
 */

namespace Pulsar\Tools;

/**
 * Asset class.
 */
class Asset {

	/**
	 * Retrieve the attribute for a matching asset file.
	 *
	 * @param string $slug The slug of the asset file, eg `app-styles`.
	 * @param string $attribute The attribute to get from the asset file. Typically `version` or `dependencies`.
	 *
	 * @return string|array
	 */
	public static function attribute( $slug, $attribute ) {
		$asset      = self::get( $slug );
		$attributes = [];

		if ( ! empty( $attribute ) && isset( $asset[ $attribute ] ) ) {
			return $asset[ $attribute ];
		}

		if ( $attribute === 'dependencies' && SCRIPT_DEBUG === true ) {
			$attributes[] = 'wp-react-refresh-runtime';
		}

		return $attributes;
	}

	/**
	 * Retrieve the a matching asset file.
	 *
	 * @param string $slug The slug of the asset file, eg `app-styles`.
	 *
	 * @return array|null
	 */
	public static function get( $slug ) {
		$asset = null;

		if ( file_exists( get_parent_theme_file_path( "dist/{$slug}.asset.php" ) ) ) {
			$asset = require get_parent_theme_file_path( "dist/{$slug}.asset.php" );
		}

		return $asset;
	}
}
