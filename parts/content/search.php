<li itemscope itemtype="https://schema.org/Thing">
	<?php the_post_thumbnail( 'post-thumbnail' ); ?>

	<span itemprop="name">
		<a href="<?php the_permalink(); ?>" itemprop="url">
			<?php the_title(); ?>
		</a>
	</span>

	<div itemprop="description">
		<?php the_excerpt(); ?>
	</div>
</li>
