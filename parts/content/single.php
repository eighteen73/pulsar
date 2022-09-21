<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( Pulsar\display_post_title() ) : ?>
		<header class="entry-header">
			<h1 class="entry-title">
				<?php the_title(); ?>
			</h1>
		</header>
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>
