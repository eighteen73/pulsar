<?php
/**
 * Responsive modifications for a menu.
 * Set on a menu via the `responsive` => true arguement to `wp_nav_menu`.
 *
 * @package Pulsar
 */

namespace Pulsar\Menu;

use Pulsar\Contracts\Bootable;
use WP_HTML_Tag_Processor;

/**
 * Responsive menu class.
 */
class Responsive implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() : void {
		add_filter( 'nav_menu_submenu_attributes', [ $this, 'submenu_attributes' ], 10, 3 );
		add_filter( 'nav_menu_item_attributes', [ $this, 'item_attributes' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'link_attributes' ], 10, 4 );
		add_filter( 'walker_nav_menu_start_el', [ $this, 'parent_markup' ], 10, 4 );
	}

	/**
	 * Alpine Attributes for submenus.
	 *
	 * @param array     $atts  The HTML attributes applied to the <ul> element, empty strings are ignored.
	 * @param \stdClass $args  An object of wp_nav_menu() arguments.
	 * @param int       $depth Depth of menu item.
	 *
	 * @return array
	 */
	public function submenu_attributes( array $atts, \stdClass $args, int $depth ) : array {

		if ( ! isset( $args->responsive ) ) {
			return $atts;
		}

		$atts['xcloak'] = 'true';

		return $atts;
	}

	/**
	 * Alpine Attributes for menu items.
	 *
	 * @param array     $atts       The HTML attributes applied to the <ul> element, empty strings are ignored.
	 * @param \WP_Post  $menu_item  An object of wp_nav_menu() arguments.
	 * @param \stdClass $args       An object of wp_nav_menu() arguments.
	 * @param int       $depth      Depth of menu item.
	 *
	 * @return array
	 */
	public function item_attributes( array $atts, \WP_Post $menu_item, \stdClass $args, int $depth ) : array {

		if ( ! isset( $args->responsive ) ) {
			return $atts;
		}

		if ( $depth === 0 ) {
			$atts['@keydown.escape']             = 'onEscape($event)';
			$atts['@pointerleave.debounce.50ms'] = 'onPointerLeave(' . $menu_item->ID . ', ' . $menu_item->menu_item_parent . ')';

			// If the parent is not a toggle, then pointer events need to be on the list item.
			if ( isset( $args->responsive['parent_as_toggle'] ) && ! $args->responsive['parent_as_toggle'] ) {
				$atts['@pointerenter.debounce.50ms'] = 'onPointerEnter(' . $menu_item->ID . ', ' . $menu_item->menu_item_parent . ')';
			}
		}

		return $atts;
	}

	/**
	 * Alpine Attributes for menu links.
	 *
	 * @param array     $atts       The HTML attributes applied to the <ul> element, empty strings are ignored.
	 * @param \WP_Post  $menu_item  An object of wp_nav_menu() arguments.
	 * @param \stdClass $args       An object of wp_nav_menu() arguments.
	 * @param int       $depth      Depth of menu item.
	 *
	 * @return array
	 */
	public function link_attributes( array $atts, \WP_Post $menu_item, \stdClass $args, int $depth ) : array {

		if ( ! isset( $args->responsive ) ) {
			return $atts;
		}

		$has_children = in_array( 'menu-item-has-children', $menu_item->classes );

		if ( $has_children && ( isset( $args->responsive['parent_as_toggle'] ) && $args->responsive['parent_as_toggle'] ) ) {
			$atts['data-dropdown']               = 'true';
			$atts['aria-haspopup']               = 'true';
			$atts[':aria-expanded']              = '(isSubMenuOpen(' . $menu_item->ID . ')).toString()';
			$atts['@click.prevent']              = 'toggleSubMenu(' . $menu_item->ID . ', ' . $menu_item->menu_item_parent . ')';
			$atts['@pointerenter.debounce.50ms'] = 'onPointerEnter(' . $menu_item->ID . ', ' . $menu_item->menu_item_parent . ')';
			$atts['@click.away']                 = 'onClickAway';

			if ( $atts['href'] === '#' ) {
				unset( $atts['href'] );
			}
		}

		return $atts;
	}

	public function parent_markup( $item_output, $menu_item, $depth, $args ) {

		if ( ! isset( $args->responsive ) ) {
			return $item_output;
		}

		$has_children = in_array( 'menu-item-has-children', $menu_item->classes );

		if ( $has_children ) {

			// Replace the anchor with a button.
			if ( isset( $args->responsive['parent_as_toggle'] ) && $args->responsive['parent_as_toggle'] ) {
				$item_output = new WP_HTML_Tag_Processor( $item_output );

				if ( $item_output->next_tag( 'a' ) ) {
					$item_output->remove_attribute( 'href' );
				}

				$item_output = $item_output->get_updated_html();
				$item_output = str_replace( '<a', '<button', $item_output );
				$item_output = str_replace( '</a>', '</button>', $item_output );

				// If an icon is set, add that to the button.
				if ( isset( $args->responsive['icon'] ) ) {
					$item_output = str_replace( '</button>', $args->responsive['icon'] . '</button>', $item_output );
				}
			}

			if ( isset( $args->responsive['parent_as_toggle'] ) && ! $args->responsive['parent_as_toggle'] ) {

				// Use the theme location as a namespace.
				$location = $args->theme_location ? $args->theme_location : 'default';

				$button_markup = sprintf(
					'<button
						aria-label="%1$s"
						class="menu-%2$s__sub-menu-toggle"
						aria-dropdown="true"
						aria-haspopup="true"
						:aria-expanded="isSubMenuOpen(%3$s).toString()"
						@click.prevent="toggleSubMenu(%3$s, %4$s)"
						@pointerenter.debounce.50ms="onPointerEnter(%3$s, %4$s)"
						click.away="onClickAway"
					>
						%5$s
					</button>',
					__( 'Toggle sub menu', 'pulsar' ),
					$location,
					$menu_item->ID,
					$menu_item->menu_item_parent,
					$args->responsive['icon'] ?? '',
				);

				$item_output = str_replace( '</a>', '</a>' . $button_markup, $item_output );
			}
		}

		return $item_output;
	}
}
