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
			<header id="masthead" class="site-header" x-data="menu()">
				<div class="container">
					<a class="screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'pulsar' ); ?></a>

					<h1 class="site-header__title"><?php bloginfo( 'name' ); ?></h1>

					<?php get_template_part( 'parts/menus/primary' ); ?>
				</div>
			</header>
