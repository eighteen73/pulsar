<?php
/**
 * The page template file.
 * Looks for a content/page-{page-slug}.php file,
 * otherwise uses content/page.php
 *
 * @package NewTheme
 */

get_header(); ?>

	<main id="content" class="site-main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'parts/content/page', get_post_field( 'post_name' ) ); ?>
		<?php endwhile; ?>
	</main>

<?php
get_footer();
