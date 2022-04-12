<?php
/**
 * The single template file
 *
 * @package NewTheme
 */

get_header(); ?>

	<main id="content" class="site-main">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php get_template_part( 'parts/content/single', get_post_type() ); ?>
		<?php endwhile; ?>
	</main>

<?php
get_footer();
