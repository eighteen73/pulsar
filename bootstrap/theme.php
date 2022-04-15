<?php

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
	static $bindings = null;

	// On first run, create new components and boot them.
	if ( is_null( $bindings ) ) {
		$bindings = Config::get( 'bindings' );

		foreach ( $bindings as $binding ) {
			$binding->boot();
		}
	}

	return $abstract ? $bindings[ $abstract ] : $bindings;
}

/**
 * Bootstrap theme.
 * Run a small bootstrapping routine.
 */
do_action( 'new_theme/booted', theme() );
