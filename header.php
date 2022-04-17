<?php
/**
 * The template for displaying the header.
 *
 * @package NewTheme
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
			<header id="masthead" class="site-header">
				<div class="container">
					<a class="sr-only" href="#content"><?php esc_html_e( 'Skip to content', 'new-theme' ); ?></a>
					<h1><?php bloginfo( 'name' ); ?></h1>
				</div>
			</header>
