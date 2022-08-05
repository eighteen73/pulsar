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

	public static function attribute( $slug, $attribute ) {
		$asset = self::get( $slug );

		if ( ! empty( $attribute ) && isset( $asset[ $attribute ] ) ) {
			return $asset[ $attribute ];
		}

		return $asset;
	}

	public static function get( $slug ) {
		$asset = null;

		if ( file_exists( get_parent_theme_file_path( "dist/{$slug}.asset.php" ) ) ) {
			$asset = require get_parent_theme_file_path( "dist/{$slug}.asset.php" );
		}

		return $asset;
	}
}
