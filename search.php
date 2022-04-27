<?php
/**
 * The template for displaying search results pages.
 *
 * @package Pulsar
 */

get_header(); ?>

	<section itemscope itemtype="https://schema.org/SearchResultsPage">
		<?php if ( have_posts() ) : ?>
			<header>
				<h1>
					<?php
					/* translators: the search query */
					printf( esc_html__( 'Search Results for: %s', 'pulsar' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
					?>
				</h1>
			</header>

			<ul>
			<?php
			while ( have_posts() ) :
				the_post();
				?>

				<?php get_template_part( 'parts/content/search', get_post_type() ); ?>

			<?php endwhile; ?>
			</ul>

			<?php the_posts_pagination(); ?>
		<?php endif; ?>
	</section>

<?php
get_footer();
