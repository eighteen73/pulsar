<?php
/**
 * Bootstrap all of the theme classes.
 *
 * @package NewTheme
 */

namespace NewTheme;

use NewTheme\Tools\Config;

/**
 * Mini container.  This allows us to set up single instances of our objects
 * without using the singleton pattern and gives third-party devs easy access to
 * the objects if they need to unhook actions/filters added by the classes.
 *
 * Child theme authors can access the objects via `theme( $abstract )`.
 *
 * @access public
 * @param  string  $abstract
 * @return mixed
 */
function theme( string $abstract = '' ) {
	static $classes = null;

	// On first run, create new components and boot them.
	if ( is_null( $classes ) ) {
		$bindings = Config::get( 'bindings' );

		foreach ( $bindings as $binding ) {
			$classes[ $binding ] = new $binding();
			$classes[ $binding ]->boot();
		}
	}

	return $abstract ? $classes[ $abstract ] : $classes;
}

/**
 * Bootstrap theme.
 * Run a small bootstrapping routine.
 */
do_action( 'new_theme/booted', theme() );
