<?php
/*
* Image row snippet
*/
?>
<div class="columns sixteen">
	<?php
	if ( has_post_thumbnail() ) {
		?>
		<div class="social-and-image-container">
			<div class="share-buttons-area social-column">
				<?php smartadapt_get_social_buttons(); ?>
			</div>
			<div class="comments-link">
				<?php if ( comments_open() && is_single() ) { ?>
					<a href="<?php echo get_comments_link(); ?>"><i class="icon-comment" data-toggle="tooltip" data-placement="top" title="" data-original-title="Comments"></i> <?php echo get_comments_number() ?></a>

				<?php } ?>
			</div>
			<div class="image-column">
				<div class="large-image-outer">

					<?php the_post_thumbnail( 'wide-image' ); ?>

				</div>
			</div>
		</div>
		<?php
	}
	else {
		?>
		<div class="share-buttons-line">
			<?php smartadapt_get_social_buttons(); ?>
		</div>
		<?php
	}
	?>


</div>