<?php
/**
 * Menu specific class modifications.
 *
 * @package Pulsar
 */

namespace Pulsar\Menu;

use Pulsar\Contracts\Bootable;

/**
 * Menu classes class.
 */
class Classes implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_filter( 'wp_nav_menu_args', [ $this, 'menu_classes' ] );
		add_filter( 'nav_menu_css_class', [ $this, 'item_classes' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'link_classes' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'submenu_classes' ], 10, 3 );
	}

	/**
	 * Adds default class to `menu_class`.
	 *
	 * @param array $args  An object of wp_nav_menu() arguments.
	 *
	 * @return array
	 */
	public function menu_classes( array $args ) {

		// Use the theme location as a namespace.
		$location = $args['theme_location'] ? $args['theme_location'] : 'default';

		// Add the base class.
		$args['menu_class'] = "menu-{$location}__items";

		return $args;
	}

	/**
	 * Modify the menu item classes.
	 *
	 * @param array     $classes  Array of the CSS classes that are applied to the menu item's <li> element.
	 * @param \WP_Post  $item     The current menu item
	 * @param \stdClass $args     An object of wp_nav_menu() arguments.
	 * @param int       $depth    Depth of menu item.
	 *
	 * @return array
	 */
	public function item_classes( array $classes, \WP_Post $item, \stdClass $args, int $depth ): array {

		// Use the theme location as a namespace.
		$location = $args->theme_location ? $args->theme_location : 'default';

		// Base classes.
		$_classes = [
			"menu-{$location}__item",
			"is-depth-{$depth}",
		];

		// Add modifiers for current item/parent/ancestor.
		foreach ( [ 'item', 'parent', 'ancestor' ] as $type ) {
			if ( in_array( "current-menu-{$type}", $classes ) || in_array( "current_page_{$type}", $classes ) ) {
				$_classes[] = 'item' === $type ? 'is-current' : "is-{$type}";
			}
		}

		// If the menu item is a post type archive and we're viewing a single
		// post of that post type, the menu item should be an ancestor.
		if ( 'post_type_archive' === $item->type && is_singular( $item->object ) && ! in_array( 'is-ancestor', $_classes ) ) {
			$_classes[] = 'is-ancestor';
		}

		// Add a class if the menu item has children.
		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$_classes[] = 'has-children';
		}

		// Add custom user-added classes if we have any.
		$custom = get_post_meta( $item->ID, '_menu_item_classes', true );

		if ( $custom ) {
			$_classes = array_merge( $_classes, (array) array_filter( $custom ) );
		}

		return $_classes;
	}

	/**
	 * Adds option 'menu_link_class' to 'wp_nav_menu'.
	 *
	 * @param array     $atts   Menu attributes.
	 * @param \WP_Post  $item   The current menu item.
	 * @param \stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int       $depth  Depth of menu item.
	 *
	 * @return array
	 */
	public function link_classes( array $atts, \WP_Post $item, \stdClass $args, int $depth ) {

		// Use the theme location as a namespace.
		$location = $args->theme_location ? $args->theme_location : 'default';

		// Base classes.
		$_classes = [
			"menu-{$location}__link",
			"is-depth-{$depth}",
		];

		// Add current menu item modifier.
		if ( in_array( 'current-menu-item', $item->classes ) || in_array( 'current_page_item', $item->classes ) ) {
			$_classes[] = 'is-current';
		}

		$atts['class'] = implode( ' ', $_classes );

		return $atts;
	}

	/**
	 * Adds option 'menu_submenu_class' to 'wp_nav_menu'.
	 *
	 * @param array     $classes  Array of the CSS classes that are applied to the menu <ul> element.
	 * @param \stdClass $args     An object of wp_nav_menu() arguments.
	 * @param int       $depth    Holds the nav menu arguments.
	 *
	 * @return array
	 */
	public function submenu_classes( array $classes, \stdClass $args, int $depth ): array {

		// Use the theme location as a namespace.
		$location = $args->theme_location ? $args->theme_location : 'default';

		// Base classes.
		$_classes = [
			"menu-{$location}__sub-menu",
			"is-depth-{$depth}",
		];

		return $_classes;
	}
}
