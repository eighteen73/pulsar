<?php
/**
 * The template for displaying the header.
 *
 * @package Pulsar
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<div id="page" class="site">
			<header id="masthead" class="site-header container">
				<div class="site-header__inner">
					<a class="screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'pulsar' ); ?></a>

					<?php Pulsar\site_logo(); ?>

					<?php get_template_part( 'parts/menu/primary' ); ?>
				</div>
			</header>
