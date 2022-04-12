<?php
/**
 * The archive template file
 *
 * @package NewTheme
 */

get_header(); ?>

	<main id="content" class="site-main">
		<?php if ( have_posts() ) : ?>
			<header>
				<h1><?php the_archive_title(); ?></h1>
				<div><?php the_archive_description(); ?></div>
			</header>

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'parts/content/archive', get_post_type() ); ?>
			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>
		<?php endif; ?>
	</main>

<?php
get_footer();
