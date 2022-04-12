<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<figure class="entry-image">
		<?php the_post_thumbnail( 'post-thumbnail' ); ?>
	</figure>

	<header class="entry-header">
		<h2 class="entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>
	</header>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
</article>
