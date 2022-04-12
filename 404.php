<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package orphansTheme
 */

get_header(); ?>

	<section>
			<header>
				<h1><?php esc_html_e( "Sorry, this page doesn't exist.", 'new-theme' ); ?></h1>
			</header>

			<div>
				<p><?php esc_html_e( "It seems we can't find what you're looking for. Perhaps searching can help.", 'new-theme' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		</section>
	</main>

<?php get_footer(); ?>
