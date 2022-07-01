<?php
/**
 * Theme functions file.
 *
 * This file is used to bootstrap the theme.
 * No other code should be placed here.
 * Instead, it should be placed in the `/includes` folder in a relevant file.
 *
 * @package Pulsar
 */

/**
 * Compatibility check.
 *
 * Check that the site meets the minimum requirements for the theme before
 * proceeding if this is a theme for public release. If building for a client
 * that meets these requirements, this code is unnecessary.
 */
if ( version_compare( $GLOBALS['wp_version'], '6.0', '<' ) || version_compare( PHP_VERSION, '7.4', '<' ) ) {
	require_once get_parent_theme_file_path( 'includes/compat.php' );
	return;
}

/**
 * Bootstrap the theme.
 *
 * Load the bootstrap files. Note that autoloading should happen first so that
 * any classes/functions are available that we might need.
 */
require_once get_parent_theme_file_path( 'includes/autoload.php' );
require_once get_parent_theme_file_path( 'includes/theme.php' );
