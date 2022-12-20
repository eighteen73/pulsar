<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( Pulsar\display_post_title() ) : ?>
		<header class="entry-header container">
			<h1 class="entry-title">
				<?php the_title(); ?>
			</h1>
		</header>
	<?php endif; ?>

	<div class="entry-content is-layout-flow is-layout-constrained has-global-padding">
		<?php the_content(); ?>
	</div>
</article>
