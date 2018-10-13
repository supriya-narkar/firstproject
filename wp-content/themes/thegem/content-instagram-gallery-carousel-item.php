<?php

$thegem_classes = array('portfolio-item');

$thegem_image_classes = array('image');
$thegem_caption_classes = array('caption');

if ($params['layout'] == '4x') {
	$thegem_classes = array_merge($thegem_classes, array('col-md-3', 'col-xs-6'));
} else {
	$thegem_classes = array_merge($thegem_classes, array('col-md-4', 'col-xs-6'));
}

if ($params['effects_enabled'])
	$thegem_classes[] = 'lazy-loading-item';

$thegem_small_image_url = array($i_image['large'], 640, 640);
$thegem_large_image_url = $i_image['original'];

?>


<div style="padding: <?php echo intval($gap_size); ?>px;" <?php post_class($thegem_classes); ?> <?php if($params['effects_enabled']): ?>data-ll-effect="move-up"<?php endif; ?> data-sort-date="<?php echo get_the_date('U'); ?>">
	<div class="wrap clearfix">
		<div <?php post_class($thegem_image_classes); ?>>
			<div class="image-inner">
				<img src="<?php echo esc_url($thegem_small_image_url[0]); ?>" width="<?php echo esc_attr($thegem_small_image_url[1]); ?>" height="<?php echo esc_attr($thegem_small_image_url[2]); ?>" alt="<?php the_title(); ?>" />
			</div>
			<div class="overlay">
				<div class="overlay-circle"></div>
				<?php $thegem_link = $thegem_large_image_url; ?>
				<a href="<?php echo esc_url($thegem_link); ?>" class="portolio-item-link full-image fancy"></a>
				<div class="links-wrapper">
					<div class="links">
						<div class="portfolio-icons">
							<a href="<?php echo esc_url($thegem_link); ?>" class="icon full-image fancy"></a>
						</div>
						<?php if($i_image['description']) : ?>
							<div class="caption">
								<div class="description">
									<div class="subtitle"><?php echo substr($i_image['description'], 0, 50); if(substr($i_image['description'], 0, 50) != $i_image['description']) echo '...'; ?></div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
