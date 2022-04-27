<?php
/**
 * The main template file
 *
 * @package Pulsar
 */

get_header(); ?>

	<main id="content" class="site-main">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<?php get_template_part( 'parts/content/archive', get_post_type() ); ?>
			<?php endwhile; ?>

			<?php the_posts_pagination(); ?>
		<?php endif; ?>
	</main>

<?php
get_footer();
