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
	 * The submenu blocks.
	 *
	 * @var array
	 */
	var $submenu_blocks = [
		'core/navigation-submenu',
		'pulsar/navigation-megamenu',
	];

	/**
	 * The icons.
	 *
	 * @var array
	 */
	var $icons = [
		'open'    => 'navigation-open',
		'close'   => 'navigation-close',
		'submenu' => 'navigation-submenu',
		'back'    => 'navigation-back',
	];

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {

		// Update icons
		add_filter( 'render_block_core/navigation', [ $this, 'modify_open_icon' ], 10, 2 );
		add_filter( 'render_block', [ $this, 'modify_submenu_icon' ], 10, 2 );

		// Add submenu header
		add_filter( 'render_block', [ $this, 'prepend_submenu_header' ], 10, 2 );
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
	public function modify_open_icon( string $block_content, array $block ): string {

		$tags = new WP_HTML_Tag_Processor( $block_content );

		$tags->next_tag( [ 'class_name' => 'wp-block-navigation__responsive-container-open' ] );
		$tags->set_attribute( 'data-wp-on--click', 'actions.toggleMenuOnClick' );

		$block_content = $tags->get_updated_html();
		$open_icon     = render_svg( $this->icons['open'] );
		$close_icon    = render_svg( $this->icons['close'] );

		// Find the closing SVG tag and add another SVG after it.
		$block_content = preg_replace('/\<svg width(.*?)\<\/svg\>/', $open_icon . $close_icon, $block_content, 1 );

		return $block_content;
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
	public function modify_submenu_icon( string $block_content, array $block ): string {

		if ( ! in_array( $block['blockName'], $this->submenu_blocks, true ) ) {
			return $block_content;
		}

		$icon = render_svg( $this->icons['submenu'] );

		// Find the closing SVG tag and add another SVG after it.
		$block_content = preg_replace( '/<svg(.*?)<\/svg>/', $icon, $block_content, 1 );

		return $block_content;
	}

	/**
	 * Prepend a header to a submenu.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The block.
	 * @return string
	 */
	public function prepend_submenu_header( string $block_content, array $block ): string {

		if ( ! in_array( $block['blockName'], $this->submenu_blocks, true ) ) {
			return $block_content;
		}

		$back_button = $this->submenu_header_markup(
			__( 'Back', 'pulsar' ),
			$block['attrs']['label'],
			$block['attrs']['url'],
			__( 'View all', 'pulsar' ) . '<span class="screen-reader-text"> ' . $block['attrs']['label'] . '</span>',
		);

		// Find the opening UL tag and add the back button within it.
		$block_content = preg_replace( '/<ul(.*?)>/', '$0' . $back_button, $block_content, 1 );

		return $block_content;
	}

	/**
	 * Render the submenu header markup.
	 *
	 * @param string $back_text The back text.
	 * @param string $label     The submenu title.
	 * @param string $all_url   The view all URL.
	 * @param string $all_text  The view all text.
	 * @return string
	 */
	public function submenu_header_markup( $back_text, $label, $all_url, $all_text ) {

		$back_icon = render_svg( $this->icons['back'] );

		return sprintf(
			'<li class="wp-block-navigation-item wp-block-navigation-submenu__header">
				<button
					class="wp-block-navigation-item__content wp-block-navigation-submenu__back"
					data-wp-on--click="actions.toggleMenuOnClick"
				>
					%1$s %2$s
				</button>

				<span class="wp-block-navigation-submenu__label">
					%3$s
				</span>

				<a href="%4$s" class="wp-block-navigation-submenu__all">
					%5$s
				</a>
			</li>',
			$back_icon,
			$back_text,
			$label,
			$all_url,
			$all_text,
		);
	}
}
