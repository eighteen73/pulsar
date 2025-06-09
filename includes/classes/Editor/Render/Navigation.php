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
	 * The classes applied to the navigation block.
	 *
	 * @var array
	 */
	protected array $classes = [
		'submenu-slide'     => 'is-submenu-style-slide',
		'submenu-accordion' => 'is-submenu-style-accordion',
		'submenu-back'      => 'has-submenu-back',
		'submenu-label'     => 'has-submenu-label',
		'submenu-all'       => 'has-submenu-all',
	];

	/**
	 * The submenu blocks.
	 *
	 * @var array
	 */
	protected array $submenu_blocks = [
		'core/navigation-submenu',
		'pulsar/navigation-megamenu',
	];

	/**
	 * The icons.
	 *
	 * @var array
	 */
	protected array $icons = [
		'open'    => 'navigation-open',
		'close'   => 'navigation-close',
		'submenu' => 'navigation-submenu',
		'back'    => 'navigation-submenu-back',
		'all'     => 'navigation-submenu-all',
	];

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {

		// Add extension classes.
		add_filter( 'render_block_core/navigation', [ $this, 'add_classes' ], 10, 2 );

		// Add context for submenu attributes.
		add_filter( 'block_type_metadata', [ $this, 'add_context' ] );

		// Update icons
		add_filter( 'render_block_core/navigation', [ $this, 'modify_open_icon' ], 10, 2 );
		add_filter( 'render_block', [ $this, 'modify_submenu_icon' ], 10, 2 );

		// Add submenu header
		add_filter( 'render_block', [ $this, 'prepend_submenu_header' ], 10, 3 );
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return true;
	}

	/**
	 * Add extension classes to the navigation block front end output.
	 * Ass the navigation block is dynamic, the classes need adding here too.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The block.
	 *
	 * @return string
	 */
	public function add_classes( string $block_content, array $block ): string {

		$is_responsive = ( ! isset( $block['attrs']['overlayMenu'] ) || ( isset( $block['attrs']['overlayMenu'] ) && $block['attrs']['overlayMenu'] !== 'never' ) ) ?? false;

		if ( ! $is_responsive ) {
			return $block_content;
		}

		$tags = new WP_HTML_Tag_Processor( $block_content );

		$tags->next_tag( [ 'class_name' => 'wp-block-navigation' ] );

		if ( isset( $block['attrs']['hasSubmenuBack'] ) && $block['attrs']['hasSubmenuBack'] ) {
			$tags->add_class( $this->classes['submenu-slide'] );
			$tags->add_class( $this->classes['submenu-back'] );
		} else {
			$tags->add_class( $this->classes['submenu-accordion'] );
		}

		if ( isset( $block['attrs']['hasSubmenuLabel'] ) && $block['attrs']['hasSubmenuLabel'] ) {
			$tags->add_class( $this->classes['submenu-label'] );
		}

		if ( isset( $block['attrs']['hasSubmenuAll'] ) && $block['attrs']['hasSubmenuAll'] ) {
			$tags->add_class( $this->classes['submenu-all'] );
		}

		$block_content = $tags->get_updated_html();

		return $block_content;
	}

	/**
	 * Add additional context to the navigation block and submenu blocks.
	 * This is used to conditionally render the submenu header and its contents.
	 *
	 * @param array $metadata The block metadata.
	 *
	 * @return array
	 */
	public function add_context( $metadata ) {
		if ( isset( $metadata['name'] ) && in_array( $metadata['name'], $this->submenu_blocks, true ) ) {
			$metadata['usesContext'] ??= [];
			$metadata['usesContext'][] = 'hasSubmenuBack';
			$metadata['usesContext'][] = 'hasSubmenuLabel';
			$metadata['usesContext'][] = 'hasSubmenuAll';
		}

		if ( isset( $metadata['name'] ) && 'core/navigation' === $metadata['name'] ) {
			$metadata['attributes']['hasSubmenuBack'] = [
				'type'    => 'boolean',
				'default' => false,
			];

			$metadata['attributes']['hasSubmenuLabel'] = [
				'type'    => 'boolean',
				'default' => false,
			];

			$metadata['attributes']['hasSubmenuAll'] = [
				'type'    => 'boolean',
				'default' => false,
			];

			$metadata['providesContext']                  ??= [];
			$metadata['providesContext']['hasSubmenuBack']  = 'hasSubmenuBack';
			$metadata['providesContext']['hasSubmenuLabel'] = 'hasSubmenuLabel';
			$metadata['providesContext']['hasSubmenuAll']   = 'hasSubmenuAll';
		}

		return $metadata;
	}

	/**
	 * Allow the navigation icon to toggle the menu, rather than just opening it by default.
	 * This is done by adding a data attribute to the icon.
	 * Also replace the SVGs with our own custom SVGs.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The block.
	 *
	 * @return string
	 */
	public function modify_open_icon( string $block_content, array $block ): string {

		$tags = new WP_HTML_Tag_Processor( $block_content );

		$tags->next_tag( [ 'class_name' => 'wp-block-navigation__responsive-container-open' ] );
		$tags->set_attribute( 'data-wp-on-async--click', 'actions.toggleMenuOnClick' );

		$block_content = $tags->get_updated_html();
		$open_icon     = render_svg( $this->icons['open'] );
		$close_icon    = render_svg( $this->icons['close'] );

		// Find the closing SVG tag and add another SVG after it.
		$block_content = preg_replace( '/\<svg width(.*?)\<\/svg\>/', $open_icon . $close_icon, $block_content, 1 );

		return $block_content;
	}

	/**
	 * Allow the navigation icon to toggle the menu, rather than just opening it by default.
	 * This is done by adding a data attribute to the icon.
	 * Also replace the SVGs with our own custom SVGs.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The block.
	 *
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
	 * @param string    $block_content The block content.
	 * @param array     $block         The block.
	 * @param \WP_Block $instance   The block instance.
	 *
	 * @return string
	 */
	public function prepend_submenu_header( string $block_content, array $block, \WP_Block $instance ): string {

		if ( ! in_array( $block['blockName'], $this->submenu_blocks, true ) ) {
			return $block_content;
		}

		$has_submenu_back  = $instance->context['hasSubmenuBack'] ??= false;
		$has_submenu_label = $instance->context['hasSubmenuLabel'] ??= false;
		$has_submenu_all   = $instance->context['hasSubmenuAll'] ??= false;

		if ( ! $has_submenu_back ) {
			return $block_content;
		}

		$back_args = $has_submenu_back ? [
			'icon' => render_svg( $this->icons['back'] ),
			'text' => __( 'Back', 'pulsar' ),
		] : false;

		$label_args = $has_submenu_label ? [
			'text' => $block['attrs']['label'],
		] : false;

		$all_args = $has_submenu_all ? [
			'url'  => $block['attrs']['url'],
			'text' => __( 'View all', 'pulsar' ) . '<span class="screen-reader-text"> ' . $block['attrs']['label'] . '</span>',
			'icon' => render_svg( $this->icons['all'] ),
		] : false;

		$back_button = $this->submenu_header_markup(
			$back_args,
			$label_args,
			$all_args,
		);

		// Find the opening UL tag and add the back button within it.
		$block_content = preg_replace( '/<ul(.*?)>/', '$0' . $back_button, $block_content, 1 );

		return $block_content;
	}

	/**
	 * Render the submenu header markup.
	 *
	 * @param array|bool $back  The back button arguments.
	 * @param array|bool $label The label arguments.
	 * @param array|bool $all   The all button arguments.
	 *
	 * @return string|bool
	 */
	public function submenu_header_markup( array|bool $back, array|bool $label, array|bool $all ): mixed {

		// Set default arguments for the back button.
		$back = $back ? wp_parse_args(
			$back,
			[
				'icon' => render_svg( $this->icons['back'] ),
				'text' => __( 'Back', 'pulsar' ),
			]
		) : false;

		// Set default arguments for the label.
		$label = $label ? wp_parse_args(
			$label,
			[
				'text' => '',
			]
		) : false;

		// Set default arguments for the all button.
		$all = $all ? wp_parse_args(
			$all,
			[
				'url'  => '',
				'text' => __( 'View all', 'pulsar' ),
				'icon' => render_svg( $this->icons['all'] ),
			]
		) : false;

		// Bail if no back button is set.
		if ( ! $back ) {
			return false;
		}

		// Build the back button markup.
		$back_markup = sprintf(
			'<button
					class="wp-block-navigation-item__content wp-block-navigation-submenu__back"
					data-wp-on--click="actions.toggleMenuOnClick"
				>
					%1$s %2$s
				</button>',
			$back['icon'],
			$back['text'],
		);

		// Build the label markup.
		$label_markup = $label ? sprintf(
			'<span class="wp-block-navigation-submenu__label">%s</span>',
			$label['text'],
		) : '';

		// Build the all button markup.
		$all_markup = $all ? sprintf(
			'<a href="%1$s" class="wp-block-navigation-submenu__all">
					%2$s %3$s
				</a>',
			$all['url'],
			$all['text'],
			$all['icon'],
		) : '';

		// Build the header markup.
		return sprintf(
			'<li class="wp-block-navigation-item wp-block-navigation-submenu__header">
				%1$s %2$s %3$s
			</li>',
			$back_markup,
			$label_markup,
			$all_markup,
		);
	}
}
