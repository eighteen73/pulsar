<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry' ); ?>>
	<?php if ( Pulsar\display_post_title() ) : ?>
		<header class="entry__header container">
			<h1 class="entry__title">
				<?php the_title(); ?>
			</h1>
		</header>
	<?php endif; ?>

	<div class="entry__content container block-vertical-spacing">
		<?php the_content(); ?>
	</div>
</article>
