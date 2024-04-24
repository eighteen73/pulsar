<?php
/**
 * Modifications to the navigation block.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor\Render;

use Pulsar\Contracts\Bootable;
use WP_HTML_Tag_Processor;
use function Pulsar\render_svg;

/**
 * Block handling.
 */
class Navigation implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_filter( 'render_block_core/navigation', [ $this, 'modify_icon' ], 10, 2 );
		add_filter( 'render_block_core/navigation-submenu', [ $this, 'prepend_back_to_submenu' ], 10, 2 );
	}

	/**
	 * Allow the navigation icon to toggle the menu, rather than just opening it by default.
	 * This is done by adding a data attribute to the icon.
	 * Also replace the SVGs with our own custom SVGs.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The block.
	 * @return string
	 */
	public function modify_icon( string $block_content, array $block ): string {

		$tags = new WP_HTML_Tag_Processor( $block_content );

		$tags->next_tag( [ 'class_name' => 'wp-block-navigation__responsive-container-open' ] );
		$tags->set_attribute( 'data-wp-on--click', 'actions.toggleMenuOnClick' );

		$block_content = $tags->get_updated_html();
		$open_icon     = render_svg( 'navigation-open' );
		$close_icon    = render_svg( 'navigation-close' );

		// Find the closing SVG tag and add another SVG after it.
		$block_content = preg_replace('/\<svg width(.*?)\<\/svg\>/', $open_icon . $close_icon, $block_content, 1 );

		return $block_content;
	}

	/**
	 * Prepend a back button to the submenu.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The block.
	 * @return string
	 */
	public function prepend_back_to_submenu( string $block_content, array $block ): string {

		$back_icon = render_svg( 'navigation-back' );

		$back_button = sprintf(
			'<li class="wp-block-navigation-item wp-block-navigation-link wp-block-navigation-back">
				<button
					class="wp-block-navigation-item__content wp-block-navigation-item__back"
					data-wp-on--click="actions.toggleMenuOnClick"
				>
					%1$s %2$s
				</button>
			</li>',
			$back_icon,
			__( 'Back', 'pulsar' ),
		);

		// Find the opening UL tag and add the back button within it.
		$block_content = preg_replace( '/<ul(.*?)>/', '$0' . $back_button, $block_content, 1 );

		return $block_content;
	}
}
