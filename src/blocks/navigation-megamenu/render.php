<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 *
 * @package Pulsar
 */

$label   = esc_html( $attributes['label'] ?? '' );
$menu_id = esc_attr( $attributes['menuId'] ?? '');

// Don't display the mega menu link if there is no label or no menu ID.
if ( ! $label || ! $menu_id ) {
	return null;
}

$css_classes = [
	'wp-block-navigation-item',
	'wp-block-navigation-submenu',
];
$css_classes = trim( implode( ' ', $css_classes ) );

$show_submenu_indicators = isset( $block->context['showSubmenuIcon'] ) && $block->context['showSubmenuIcon'];
$open_on_click           = isset( $block->context['openSubmenusOnClick'] ) && $block->context['openSubmenusOnClick'];
$open_on_hover_and_click = isset( $block->context['openSubmenusOnClick'] ) && ! $block->context['openSubmenusOnClick'] && $show_submenu_indicators;

$is_active = false;

$wrapper_attributes = get_block_wrapper_attributes(
	[
		'class' => $css_classes . ( $menu_id ? ' has-child' : '' ) .
		( $open_on_click ? ' open-on-click' : '' ) . ( $open_on_hover_and_click ? ' open-on-hover-click' : '' ) .
		( $is_active ? ' current-menu-item' : '' ),
	],
);

$aria_label = sprintf(
	/* translators: Accessibility text. %s: Parent page title. */
	__( '%s submenu' ),
	wp_strip_all_tags( $label )
);

$html = '<li ' . $wrapper_attributes . '>';

// If Submenus open on hover, we render an anchor tag with attributes.
// If submenu icons are set to show, we also render a submenu button, so the submenu can be opened on click.
if ( ! $open_on_click ) {
	$item_url = isset( $attributes['url'] ) ? $attributes['url'] : '';
	// Start appending HTML attributes to anchor tag.
	$html .= '<a class="wp-block-navigation-item__content"';

	// The href attribute on a and area elements is not required;
	// when those elements do not have href attributes they do not create hyperlinks.
	// But also The href attribute must have a value that is a valid URL potentially
	// surrounded by spaces.
	// see: https://html.spec.whatwg.org/multipage/links.html#links-created-by-a-and-area-elements.
	if ( ! empty( $item_url ) ) {
		$html .= ' href="' . esc_url( $item_url ) . '"';
	}

	if ( $is_active ) {
		$html .= ' aria-current="page"';
	}

	if ( isset( $attributes['opensInNewTab'] ) && true === $attributes['opensInNewTab'] ) {
		$html .= ' target="_blank"  ';
	}

	if ( isset( $attributes['rel'] ) ) {
		$html .= ' rel="' . esc_attr( $attributes['rel'] ) . '"';
	} elseif ( isset( $attributes['nofollow'] ) && $attributes['nofollow'] ) {
		$html .= ' rel="nofollow"';
	}

	if ( isset( $attributes['title'] ) ) {
		$html .= ' title="' . esc_attr( $attributes['title'] ) . '"';
	}

	$html .= '>';
	// End appending HTML attributes to anchor tag.

	$html .= $label;
	$html .= '</a>';
	// End anchor tag content.

	if ( $show_submenu_indicators ) {
		// The submenu icon is rendered in a button here
		// so that there's a clickable element to open the submenu.
		$html .= '<button aria-label="' . esc_attr( $aria_label ) . '" class="wp-block-navigation__submenu-icon wp-block-navigation-submenu__toggle" aria-expanded="false">' . block_core_navigation_submenu_render_submenu_icon() . '</button>';
	}
} else {
	// If menus open on click, we render the parent as a button.
	$html .= '<button aria-label="' . esc_attr( $aria_label ) . '" class="wp-block-navigation-item__content wp-block-navigation-submenu__toggle" aria-expanded="false">';

	// Wrap title with span to isolate it from submenu icon.
	$html .= '<span class="wp-block-navigation-item__label">';
	$html .= $label;
	$html .= '</span>';
	$html .= '</button>';
	$html .= '<span class="wp-block-navigation__submenu-icon wp-block-pulsar-navigation-megamenu__icon">' . block_core_navigation_submenu_render_submenu_icon() . '</span>';
}

if ( $menu_id ) {
	// Copy some attributes from the parent block to this one.
	// Ideally this would happen in the client when the block is created.
	if ( array_key_exists( 'overlayTextColor', $block->context ) ) {
		$attributes['textColor'] = $block->context['overlayTextColor'];
	}
	if ( array_key_exists( 'overlayBackgroundColor', $block->context ) ) {
		$attributes['backgroundColor'] = $block->context['overlayBackgroundColor'];
	}
	if ( array_key_exists( 'customOverlayTextColor', $block->context ) ) {
		$attributes['style']['color']['text'] = $block->context['customOverlayTextColor'];
	}
	if ( array_key_exists( 'customOverlayBackgroundColor', $block->context ) ) {
		$attributes['style']['color']['background'] = $block->context['customOverlayBackgroundColor'];
	}

	// This allows us to be able to get a response from wp_apply_colors_support.
	$block->block_type->supports['color'] = true;
	$colors_supports                      = wp_apply_colors_support( $block->block_type, $attributes );
	$css_classes                          = 'wp-block-navigation__submenu-container wp-block-pulsar-navigation-megamenu__container';
	if ( array_key_exists( 'class', $colors_supports ) ) {
		$css_classes .= ' ' . $colors_supports['class'];
	}

	$style_attribute = '';
	if ( array_key_exists( 'style', $colors_supports ) ) {
		$style_attribute = $colors_supports['style'];
	}

	$template_part = get_block_template( $menu_id, 'wp_template_part' );
	$template_part = $template_part ? do_blocks( $template_part->content ) : '';

	$wrapper_attributes = get_block_wrapper_attributes(
		[
			'class' => $css_classes,
			'style' => $style_attribute,
		],
	);

	$html .= sprintf(
		'<div %s %s>%s</div>',
		'data-wp-on--focus="actions.openMenuOnFocus"',
		$wrapper_attributes,
		$template_part,
	);
}

$html .= '</li>';

echo $html;
