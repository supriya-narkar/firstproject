<?php

function thegem_slideshow_block($params = array()) {
	if($params['slideshow_type'] == 'LayerSlider') {
		if($params['lslider']) {
			echo '<div class="preloader slideshow-preloader"><div class="preloader-spin"></div></div><div class="gem-slideshow">';
			echo do_shortcode('[layerslider id="'.$params['lslider'].'"]');
			echo '</div>';
		}
	} elseif($params['slideshow_type'] == 'revslider') {
		if($params['slider']) {
			echo '<div class="preloader slideshow-preloader"><div class="preloader-spin"></div></div><div class="gem-slideshow">';
			echo do_shortcode('[rev_slider alias="'.$params['slider'].'"]');
			echo '</div>';
		}
	} elseif($params['slideshow_type'] == 'NivoSlider') {
		echo '<div class="preloader slideshow-preloader"><div class="preloader-spin"></div></div><div class="gem-slideshow">';
		thegem_nivoslider($params);
		echo '</div>';
	}
}

/* QUICKFINDER BLOCK */

function thegem_quickfinder($params) {
	$params = is_array($params) ? $params : array();
	$params = array_merge(array(
		'quickfinders' => '',
		'style' => 'default',
		'columns' => 4,
		'alignment' => 'center',
		'icon_position' => 'top',
		'title_weight' => 'bold',
		'activate_button' => 0,
		'button_style' => 'flat',
		'button_text_weight' => 'normal',
		'button_corner' => 3,
		'button_text_color' => '',
		'button_background_color' => '',
		'button_border_color' => '',
		'hover_icon_color' => '',
		'hover_box_color' => '',
		'hover_border_color' => '',
		'hover_title_color' => '',
		'hover_description_color' => '',
		'hover_button_background_color' => '',
		'hover_button_text_color' => '',
		'hover_button_border_color' => '',
		'box_style' => 'solid',
		'box_background_color' => '',
		'box_border_color' => '',
		'connector_color' => '',
		'effects_enabled' => ''
	), $params);
	$params['style'] = thegem_check_array_value(array('default', 'classic', 'iconed', 'binded', 'binded-iconed', 'tag', 'vertical-1', 'vertical-2', 'vertical-3', 'vertical-4'), $params['style'], 'default');
	$params['columns'] = thegem_check_array_value(array(1,2,3,4,6), $params['columns'], 4);
	$params['alignment'] = thegem_check_array_value(array('center', 'left', 'right'), $params['alignment'], 'center');
	$params['icon_position'] = thegem_check_array_value(array('top', 'bottom', 'top-float', 'center-float'), $params['icon_position'], 'top');
	$params['title_weight'] = thegem_check_array_value(array('bold', 'thin'), $params['title_weight'], 'bold');
	$params['activate_button'] = $params['activate_button'] ? 1 : 0;
	$params['hover_icon_color'] = esc_attr($params['hover_icon_color']);
	$params['hover_box_color'] = esc_attr($params['hover_box_color']);
	$params['hover_border_color'] = esc_attr($params['hover_border_color']);
	$params['hover_title_color'] = esc_attr($params['hover_title_color']);
	$params['hover_description_color'] = esc_attr($params['hover_description_color']);
	$params['hover_button_background_color'] = esc_attr($params['hover_button_background_color']);
	$params['hover_button_text_color'] = esc_attr($params['hover_button_text_color']);
	$params['hover_button_border_color'] = esc_attr($params['hover_button_border_color']);
	$params['box_style'] = thegem_check_array_value(array('solid', 'soft-outlined', 'strong-outlined'), $params['box_style'], 'solid');
	$params['box_background_color'] = esc_attr($params['box_background_color']);
	$params['box_border_color'] = esc_attr($params['box_border_color']);
	$params['connector_color'] = esc_attr($params['connector_color']);
	$params['effects_enabled'] = $params['effects_enabled'] ? 1 : 0;

	$hover_data = '';
	if($params['hover_icon_color']) {
		$hover_data .= ' data-hover-icon-color="'.$params['hover_icon_color'].'"';
	}
	if($params['hover_box_color']) {
		$hover_data .= ' data-hover-box-color="'.$params['hover_box_color'].'"';
	}
	if($params['hover_border_color']) {
		$hover_data .= ' data-hover-border-color="'.$params['hover_border_color'].'"';
	}
	if($params['hover_title_color']) {
		$hover_data .= ' data-hover-title-color="'.$params['hover_title_color'].'"';
	}
	if($params['hover_description_color']) {
		$hover_data .= ' data-hover-description-color="'.$params['hover_description_color'].'"';
	}
	if($params['hover_button_background_color']) {
		$hover_data .= ' data-hover-button-background-color="'.$params['hover_button_background_color'].'"';
	}
	if($params['hover_button_text_color']) {
		$hover_data .= ' data-hover-button-text-color="'.$params['hover_button_text_color'].'"';
	}
	if($params['hover_button_border_color']) {
		$hover_data .= ' data-hover-button-border-color="'.$params['hover_button_border_color'].'"';
	}
	$binded = '';
	if($params['style'] == 'binded') {
		$params['style'] = 'classic';
		$binded = ' quickfinder-binded';
	}
	if($params['style'] == 'binded-iconed') {
		$params['style'] = 'iconed';
		$binded = ' quickfinder-binded';
	}

	$args = array(
		'post_type' => 'thegem_qf_item',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['quickfinders'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_quickfinders',
				'field' => 'slug',
				'terms' => explode(',', $params['quickfinders'])
			)
		);
	}
	$quickfinder_items = new WP_Query($args);

	$quickfinder_style = $params['style'];
	$quickfinder_item_rotation = 'odd';

	$connector_color = $params['connector_color'];
	if(($quickfinder_style == 'vertical-1' || $quickfinder_style == 'vertical-2' || $quickfinder_style == 'vertical-3' || $quickfinder_style == 'vertical-4') && !$connector_color) {
		$connector_color = '#b6c6c9';
	}

	if($quickfinder_items->have_posts()) {
		wp_enqueue_script('thegem-quickfinders-effects');
		echo '<div class="quickfinder quickfinder-style-'.$params['style'].$binded.' '.($quickfinder_style == 'vertical-1' || $quickfinder_style == 'vertical-2'  || $quickfinder_style == 'vertical-3' || $quickfinder_style == 'vertical-4' ? 'quickfinder-style-vertical' : 'row inline-row').' quickfinder-icon-position-'.$params['icon_position'].' quickfinder-alignment-'.$params['alignment'].' quickfinder-title-'.$params['title_weight'].'"'.$hover_data.'>';
		while($quickfinder_items->have_posts()) {
			$quickfinder_items->the_post();
			include(locate_template(array('gem-templates/quickfinders/content-quickfinder-item-'.$params['style'].'.php', 'gem-templates/quickfinders/content-quickfinder-item.php')));
			$quickfinder_item_rotation = $quickfinder_item_rotation == 'odd' ? 'even' : 'odd';
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

function thegem_quickfinder_block($params) {
	echo '<div class="container">';
	thegem_quickfinder($params);
	echo '</div>';
}

/* TEAM BLOCK */

function thegem_team($params) {
	$params = array_merge(array('team' => '', 'team_person' => '', 'style' => '', 'columns' => '2'), $params);
	$args = array(
		'post_type' => 'thegem_team_person',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['team'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_teams',
				'field' => 'slug',
				'terms' => explode(',', $params['team'])
			)
		);
	} elseif($params['team_person'] != '') {
		$args['p'] = $params['team_person'];
	}
	$persons = new WP_Query($args);

	if($persons->have_posts()) {
		echo '<div class="gem-team row inline-row gem-team-'.esc_attr($params['style']).'">';
		while($persons->have_posts()) {
			$persons->the_post();
			include(locate_template(array('gem-templates/teams/content-team-person-'.$params['style'].'.php', 'gem-templates/teams/content-team-person.php')));
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

/* GALLERY */

function thegem_gallery($params) {
	$params = array_merge(array('gallery' => 0, 'hover' => 'default', 'layout' => 'fullwidth', 'no_thumbs' => 0, 'pagination' => 0, 'autoscroll' => 0), $params);

	if(metadata_exists('post', $params['gallery'], 'thegem_gallery_images')) {
		$thegem_gallery_images_ids = get_post_meta($params['gallery'], 'thegem_gallery_images', true);
	} else {
		$attachments_ids = get_posts('post_parent=' . $params['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$thegem_gallery_images_ids = implode(',', $attachments_ids);
	}
	$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));
	$gallery_uid = uniqid();
?>
<?php if(count($attachments_ids)) : wp_enqueue_script('thegem-gallery'); wp_enqueue_style('thegem-animations'); ?>
	<div class="preloader"><div class="preloader-spin"></div></div>
	<div class="gem-gallery gem-gallery-hover-<?php echo esc_attr($params['hover']); ?><?php echo ($params['no_thumbs'] ? ' no-thumbs' : ''); ?><?php echo ($params['pagination'] ? ' with-pagination' : ''); ?>"<?php echo (intval($params['autoscroll']) ? ' data-autoscroll="'.intval($params['autoscroll']).'"' : ''); ?>>
	<?php foreach($attachments_ids as $attachment_id) : ?>
		<?php
			$item = get_post($attachment_id);
			if($item) {
				$thumb_image_url = thegem_generate_thumbnail_src($item->ID, 'thegem-post-thumb-small');
				$preview_image_url = thegem_generate_thumbnail_src($item->ID, 'thegem-gallery-'.esc_attr($params['layout']));
				$full_image_url = wp_get_attachment_image_src($item->ID, 'full');
			}
		?>
		<?php if(!empty($thumb_image_url[0]) && $item) : ?>
			<div class="gem-gallery-item">
				<div class="gem-gallery-item-image">
					<a href="<?php echo $preview_image_url[0]; ?>" data-fancybox-group="gallery-<?php echo esc_attr($gallery_uid); ?>" data-full-image-url="<?php echo esc_attr($full_image_url[0]); ?>">
						<svg width="20" height="10"><path d="M 0,10 Q 9,9 10,0 Q 11,9 20,10" /></svg>
						<img src="<?php echo $thumb_image_url[0]; ?>" alt="<?php echo get_post_meta($attachment_id, '_wp_attachment_image_alt', true); ?>" class="img-responsive">
						<span class="gem-gallery-caption slide-info">
							<?php if($item->post_excerpt) : ?><span class="gem-gallery-item-title "><?php echo apply_filters('the_excerpt', $item->post_excerpt); ?></span><?php endif; ?>
							<?php if($item->post_content) : ?><span class="gem-gallery-item-description"><?php echo apply_filters('the_content', $item->post_content); ?></span><?php endif; ?>
						</span>
					</a>
					<span class="gem-gallery-line"></span>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
}

function thegem_simple_gallery($params) {
	$params = array_merge(array('gallery' => 0, 'thumb_size' => 'thegem-gallery-simple', 'autoscroll' => 0, 'responsive' => 0), $params);

	if(metadata_exists('post', $params['gallery'], 'thegem_gallery_images')) {
		$thegem_gallery_images_ids = get_post_meta($params['gallery'], 'thegem_gallery_images', true);
	} else {
		$attachments_ids = get_posts('post_parent=' . $params['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids');
		$thegem_gallery_images_ids = implode(',', $attachments_ids);
	}
	$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));
	$gallery_uid = uniqid();
?>
<?php if(count($attachments_ids)) : wp_enqueue_script('thegem-gallery'); wp_enqueue_style('thegem-animations'); ?>
	<div class="preloader"><div class="preloader-spin"></div></div>
	<div class="gem-simple-gallery<?php echo ($params['responsive'] ? ' responsive' : ''); ?>"<?php echo (intval($params['autoscroll']) ? ' data-autoscroll="'.intval($params['autoscroll']).'"' : ''); ?>>
	<?php foreach($attachments_ids as $attachment_id) : ?>
		<?php
			$item = get_post($attachment_id);
			if($item) {
				$thumb_size = $params['thumb_size'];
				if ($thumb_size == 'thegem-blog-timeline-large') {
					$thumb_size = 'thegem-blog-default-large';
				}
				if ($thumb_size == 'thegem-blog-multi-author') {
					$thumb_size = 'thegem-blog-default-large';
				}
				if ($thumb_size == 'thegem-blog-masonry-100' || $thumb_size == 'thegem-blog-masonry-2x' || $thumb_size == 'thegem-blog-masonry-3x' || $thumb_size == 'thegem-blog-masonry-4x') {
					$thumb_size = 'thegem-blog-masonry';
				}
				if ($thumb_size == 'thegem-blog-justified-2x' || $thumb_size == 'thegem-blog-justified-3x' || $thumb_size == 'thegem-blog-justified-4x') {
					$thumb_size = 'thegem-blog-justified';
				}
				$thumb_image_url = thegem_generate_thumbnail_src($item->ID, $thumb_size);
				$full_image_url = wp_get_attachment_image_src($item->ID, 'full');

				$thegem_sources = array();
				if ($params['thumb_size'] == 'thegem-gallery-simple') {
					$thegem_sources = array(
						array('srcset' => array('1x' => 'thegem-gallery-simple-1x', '2x' => 'thegem-gallery-simple'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-timeline-large') {
					$thegem_sources = array(
						array('media' => '(max-width: 768px)', 'srcset' => array('' => 'thegem-blog-timeline-large')),
						array('media' => '(max-width: 1050px)', 'srcset' => array('' => 'thegem-blog-timeline-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-timeline')),
						array('srcset' => array('2x' => 'thegem-blog-default-large'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-multi-author') {
					if (is_active_sidebar('page-sidebar')) {
						$thegem_sources = array(
							array('media' => '(min-width: 992px) and (max-width: 1080px)', 'srcset' => array('' => 'thegem-blog-default-small')),
							array('media' => '(max-width: 992px), (min-width: 1080px) and (max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-default-medium')),
							array('srcset' => array('2x' => 'thegem-blog-default-large'))
						);
					} else {
						$thegem_sources = array(
							array('media' => '(max-width: 1075px)', 'srcset' => array('' => 'thegem-blog-default-medium')),
							array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-default-large')),
							array('srcset' => array('2x' => 'thegem-blog-default-large'))
						);
					}
				}
				if ($params['thumb_size'] == 'thegem-blog-masonry-100') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-masonry')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-masonry-100-medium')),
						array('media' => '(max-width: 1100px)', 'srcset' => array('' => 'thegem-blog-masonry-100-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-masonry-100')),
						array('srcset' => array('2x' => 'thegem-blog-masonry'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-masonry-2x') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-masonry')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-masonry-100-medium')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-masonry-100')),
						array('srcset' => array('2x' => 'thegem-blog-masonry'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-masonry-3x') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-masonry')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-masonry-100-medium')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-masonry-100')),
						array('srcset' => array('2x' => 'thegem-blog-masonry'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-masonry-4x') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-masonry')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-masonry-100-medium')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-masonry-4x')),
						array('srcset' => array('2x' => 'thegem-blog-masonry'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-justified-2x') {
					$thegem_sources = array(
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-justified')),
						array('media' => '(max-width: 1100px)', 'srcset' => array('' => 'thegem-blog-justified-3x-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-justified-3x')),
						array('srcset' => array('2x' => 'thegem-blog-justified'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-justified-3x') {
					$thegem_sources = array(
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-justified')),
						array('media' => '(max-width: 1100px)', 'srcset' => array('' => 'thegem-blog-justified-3x-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-justified-3x')),
						array('srcset' => array('2x' => 'thegem-blog-justified'))
					);
				}
				if ($params['thumb_size'] == 'thegem-blog-justified-4x') {
					$thegem_sources = array(
						array('media' => '(max-width: 600px)', 'srcset' => array('' => 'thegem-blog-justified')),
						array('media' => '(max-width: 992px)', 'srcset' => array('' => 'thegem-blog-justified-4x')),
						array('media' => '(max-width: 1125px)', 'srcset' => array('' => 'thegem-blog-justified-3x-small')),
						array('media' => '(max-width: 1920px)', 'srcset' => array('' => 'thegem-blog-justified-4x')),
						array('srcset' => array('2x' => 'thegem-blog-justified'))
					);
				}
			}
		?>
		<?php if(!empty($thumb_image_url[0]) && $item) : ?>
			<div class="gem-gallery-item">
				<div class="gem-gallery-item-image">
					<a href="<?php echo esc_attr($full_image_url[0]); ?>" class="fancy-gallery" data-fancybox="gallery-<?php echo esc_attr($gallery_uid); ?>">
						<?php thegem_generate_picture($item->ID, $thumb_size, $thegem_sources, array('class' => 'img-responsive', 'alt' => get_post_meta($attachment_id, '_wp_attachment_image_alt', true))); ?>
					</a>
				</div>
				<div class="gem-gallery-caption">
					<?php if($item->post_excerpt) : ?><div class="gem-gallery-item-title"><?php echo apply_filters('the_excerpt', $item->post_excerpt); ?></div><?php endif; ?>
					<?php if($item->post_content) : ?><div class="gem-gallery-item-description"><?php echo apply_filters('the_content', $item->post_content); ?></div><?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php
}

function thegem_clients($params) {
	$params = array_merge(array(
		'clients_set' => '',
		'rows' => '3',
		'cols' => '3',
		'autoscroll' => '',
		'effects_enabled' => false,
		'disable_grayscale' => false,
		'widget' => false,
		'disable_carousel' => false,
	), $params);
	$args = array(
		'post_type' => 'thegem_client',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['clients_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_clients_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['clients_set'])
			)
		);
	}

	$clients_items = new WP_Query($args);

	$rows = ((int)$params['rows']) ? (int)$params['rows'] : 3;
	$cols = ((int)$params['cols']) ? (int)$params['cols'] : 3;

	$items_per_slide = $rows * $cols;
	$params['autoscroll'] = intval($params['autoscroll']);

	if($clients_items->have_posts()) {

		wp_enqueue_script('thegem-clients-grid-carousel');
		if(!$params['disable_carousel']) {
			echo '<div class="preloader"><div class="preloader-spin"></div></div>';
		}
		echo '<div class="gem-clients gem-clients-type-carousel-grid '.($params['disable_carousel'] ? 'carousel-disabled ' : '') . ($params['disable_grayscale'] ? 'disable-grayscale ' : '') . ($params['effects_enabled'] ? 'lazy-loading' : '') . '" ' . ($params['effects_enabled'] ? 'data-ll-item-delay="0"' : '') . ' data-autoscroll="'.esc_attr($params['autoscroll']).'">';
		echo '<div class="gem-clients-slide"><div class="gem-clients-slide-inner clearfix">';
		$i = 0;
		while($clients_items->have_posts()) {
			$clients_items->the_post();
			if($i == $items_per_slide) {
				echo '</div></div><div class="gem-clients-slide"><div class="gem-clients-slide-inner clearfix">';
				$i = 0;
			}
			include(locate_template('content-clients-carousel-grid-item.php'));
			$i++;
		}
		echo '</div></div>';
		echo '</div>';
	}
	wp_reset_postdata();
}

function thegem_testimonialss($params) {
	$params = array_merge(array('testimonials_set' => '', 'fullwidth' => '', 'autoscroll' => 0), $params);
	$args = array(
		'post_type' => 'thegem_testimonial',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['testimonials_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_testimonials_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['testimonials_set'])
			)
		);
	}
	$testimonials_items = new WP_Query($args);

	if($testimonials_items->have_posts()) {
		wp_enqueue_script('thegem-testimonials-carousel');
		echo '<div class="preloader"><div class="preloader-spin"></div></div>';
		echo '<div class="'.$params['image_size'].' '.$params['style'].' gem-testimonials'.( $params['fullwidth'] ? ' fullwidth-block' : '' ).'"'.( intval($params['autoscroll']) ? ' data-autoscroll="'.intval($params['autoscroll']).'"' : '' ).'>';
		while($testimonials_items->have_posts()) {
			$testimonials_items->the_post();
			include(locate_template('content-testimonials-carousel-item.php'));
		}

		echo '<div  class="testimonials_svg"><svg width="100" height="50"><path d="M 0,-1 Q 45,5 50,50 Q 55,5 100,-1" /></svg></div>';

		echo '</div>';
	}
	wp_reset_postdata();
}

function portolios_cmp($term1, $term2) {
	$order1 = get_option('portfoliosets_' . $term1->term_id . '_order', 0);
	$order2 = get_option('portfoliosets_' . $term2->term_id . '_order', 0);
	if($order1 == $order2)
		return 0;
	return $order1 > $order2;
}

// Print Portfolio Block
function thegem_portfolio($params) {
	$params = array_merge(
		array(
			'portfolio' => '',
			'title' => '',
			'layout' => '3x',
			'layout_version' => 'fullwidth',
			'caption_position' => 'right',
			'style' => 'justified',
			'gaps_size' => 42,
			'display_titles' => 'page',
			'background_style' => 'white',
			'title_style' => 'light',
			'hover' => '',
			'pagination' => 'normal',
			'loading_animation' => 'move-up',
			'items_per_page' => 8,
			'with_filter' => false,
			'show_info' => false,
			'is_ajax' => false,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'likes' => false,
			'sorting' => false,
			'all_text' => __('Show All', 'thegem'),
			'orderby' => '',
			'order' => '',
			'button' => array(),
			'metro_max_row_height' => 380
		),
		$params
	);

	$params['button'] = array_merge(array(
		'text' => __('Load More', 'thegem'),
		'style' => 'flat',
		'size' => 'medium',
		'text_weight' => 'normal',
		'no_uppercase' => 0,
		'corner' => 25,
		'border' => 2,
		'text_color' => '',
		'background_color' => '#00bcd5',
		'border_color' => '',
		'hover_text_color' => '',
		'hover_background_color' => '',
		'hover_border_color' => '',
		'icon_pack' => 'elegant',
		'icon_elegant' => '',
		'icon_material' => '',
		'icon_fontawesome' => '',
		'icon_userpack' => '',
		'icon_position' => 'left',
		'separator' => 'load-more',
	), $params['button']);

	$params['button']['icon'] = '';
	if($params['button']['icon_elegant'] && $params['button']['icon_pack'] == 'elegant') {
		$params['button']['icon'] = $params['button']['icon_elegant'];
	}
	if($params['button']['icon_material'] && $params['button']['icon_pack'] == 'material') {
		$params['button']['icon'] = $params['button']['icon_material'];
	}
	if($params['button']['icon_fontawesome'] && $params['button']['icon_pack'] == 'fontawesome') {
		$params['button']['icon'] = $params['button']['icon_fontawesome'];
	}
	if($params['button']['icon_userpack'] && $params['button']['icon_pack'] == 'userpack') {
		$params['button']['icon'] = $params['button']['icon_userpack'];
	}

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');

	$gap_size = round(intval($params['gaps_size'])/2);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	if ($params['sorting'] == 'false')
		$params['sorting'] = false;

	if ($params['sorting'] == 'true')
		$params['sorting'] = true;

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_style('thegem-animations');
	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('isotope-js');
	wp_enqueue_script('thegem-isotope-metro');
	wp_enqueue_script('thegem-isotope-masonry-custom');
	wp_enqueue_script('thegem-scroll-monitor');
	wp_enqueue_script('jquery-transform');
	wp_enqueue_script('thegem-items-animations');
	wp_enqueue_script('thegem-portfolio');

	$portfolio_uid = substr( md5(rand()), 0, 7);

	$localize = array_merge(
		array('data' => $params),
		array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('portfolio_ajax-nonce')
		)
	);
	wp_localize_script('thegem-portfolio', 'portfolio_ajax_'.$portfolio_uid, $localize);

	$layout_columns_count = -1;
	if($params['layout'] == '1x')
		$layout_columns_count = 1;
	if($params['layout'] == '2x')
		$layout_columns_count = 2;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;
	if($params['layout'] == '4x')
		$layout_columns_count = 4;

	$items_per_page = intval($params['items_per_page']) ? intval($params['items_per_page']) : 8;

	$page = 1;
	$next_page = 0;

	if($params['pagination'] == 'more' || $params['pagination'] == 'scroll') {
		$count = $items_per_page;
		if(isset($params['more_count'])) {
			$count = intval($params['more_count']);
		}
		if($layout_columns_count == -1)
			$layout_columns_count = 5;
		if($count == 0)
			$count = $layout_columns_count * 2;
		$page = isset($params['more_page']) ? intval($params['more_page']) : 1;
		if($page == 0)
			$page = 1;
		$portfolio_loop = new WP_Query(array(
			'post_type' => 'thegem_pf_item',
			'tax_query' =>$params['portfolio'] ? array(
				array(
					'taxonomy' => 'thegem_portfolios',
					'field' => 'slug',
					'terms' => explode(',', $params['portfolio'])
				)
			) : array(),
			'post_status' => 'publish',
			'orderby' => $params['orderby'] ? $params['orderby'] : ($params['sorting'] ? 'date' :'menu_order ID'),
			'order' => $params['order'] ? $params['order'] : ($params['sorting'] ? 'DESC' :'ASC'),
			'posts_per_page' => $count,
			'paged' => $page
		));
		if($portfolio_loop->max_num_pages > $page)
			$next_page = $page + 1;
		else
			$next_page = 0;
	} else {
		$portfolio_loop = new WP_Query(array(
			'post_type' => 'thegem_pf_item',
			'tax_query' =>$params['portfolio'] ? array(
				array(
					'taxonomy' => 'thegem_portfolios',
					'field' => 'slug',
					'terms' => explode(',', $params['portfolio'])
				)
			) : array(),
			'post_status' => 'publish',
			'orderby' => $params['orderby'] ? $params['orderby'] : ($params['sorting'] ? 'date' :'menu_order ID'),
			'order' => $params['order'] ? $params['order'] : ($params['sorting'] ? 'DESC' :'ASC'),
			'posts_per_page' => -1,
		));
	}

	$terms = array();

	$row_styles = '';
	if ($params['layout'] == '100%') {
	    $row_styles .= 'margin: 0;';
	    if ($gap_size) {
	        if (thegem_get_option('page_padding_left')) {
	            $row_styles .= 'margin-left: -' . $gap_size . 'px;';
	        } else {
	            $row_styles .= 'padding-left: ' . $gap_size . 'px;';
	        }

	        if (thegem_get_option('page_padding_right')) {
	            $row_styles .= 'margin-right: -' . $gap_size . 'px;';
	        } else {
	            $row_styles .= 'padding-right: ' . $gap_size . 'px;';
	        }
	    }
	} else {
	    if ($gap_size) {
	        $row_styles .= 'margin: -' . $gap_size . 'px;';
	    } else {
	        $row_styles .= 'margin: 0;';
	    }
	}

	$portfolio_title = $params['title'] ? $params['title'] : '';
	global $post;
	$portfolio_posttemp = $post;
?>
<?php if($portfolio_loop->have_posts()) : ?>
	<?php
		if($params['portfolio']) {
			$terms = explode(',', $params['portfolio']);
			foreach($terms as $key => $term) {
				$terms[$key] = get_term_by('slug', $term, 'thegem_portfolios');
				if(!$terms[$key]) {
					unset($terms[$key]);
				}
			}
		} else {
			$terms = get_terms('thegem_portfolios', array('hide_empty' => false));
		}

		$terms = apply_filters('portfolio_terms_filter', $terms);

		usort($terms, 'portolios_cmp');
		$thegem_terms_set = array();
		foreach ($terms as $term) {
			$thegem_terms_set[$term->slug] = $term;
		}
	?>

	<?php if(!$params['is_ajax']) : ?>
		<?php echo apply_filters('portfolio_preloader_filter', '<div class="preloader"><div class="preloader-spin"></div></div>'); ?>
		<div class="portfolio-preloader-wrapper">
		<?php if($portfolio_title): ?>
			<h3 class="title portfolio-title"><?php echo $portfolio_title; ?></h3>
		<?php endif; ?>

		<?php

			$portfolio_classes = array(
				'portfolio',
				'no-padding',
				'portfolio-pagination-' . $params['pagination'],
				'portfolio-style-' . $params['style'],
				'background-style-' . $params['background_style'],
				'title-style-' . $params['title_style'],
				'hover-' . esc_attr($params['hover']),
				'item-animation-' . $params['loading_animation'],
				'title-on-' . $params['display_titles']
			);

			if ($gap_size == 0) {
				$portfolio_classes[] = 'no-gaps';
			}

			if ($params['layout'] == '100%') {
				$portfolio_classes[] = 'fullwidth-columns-' . intval($params['fullwidth_columns']);
			}

			if ($params['display_titles'] == 'page' && $params['hover'] == 'gradient') {
				$portfolio_classes[] = 'hover-gradient-title';
			}

			if ($params['display_titles'] == 'page' && $params['hover'] == 'circular') {
				$portfolio_classes[] = 'hover-circular-title';
			}

			if ($params['display_titles'] == 'hover' || $params['hover'] == 'gradient' || $params['hover'] == 'circular') {
				$portfolio_classes[] = 'hover-title';
			}

			if ($params['style'] == 'masonry' && $params['layout'] != '1x') {
				$portfolio_classes[] = 'portfolio-items-masonry';
			}

			if ($layout_columns_count != -1) {
				$portfolio_classes[] = 'columns-' . intval($layout_columns_count);
			}

			$portfolio_classes = apply_filters('portfolio_classes_filter', $portfolio_classes);

		?>

			<div data-per-page="<?php echo $items_per_page; ?>" data-portfolio-uid="<?php echo esc_attr($portfolio_uid); ?>" class="<?php echo implode(' ', $portfolio_classes); ?>" data-hover="<?php echo $params['hover']; ?>" <?php if($params['pagination'] == 'more' || $params['pagination'] == 'scroll'): ?>data-next-page="<?php echo esc_attr($next_page); ?>"<?php endif; ?>>
				<?php if(($params['with_filter'] && count($terms) > 0) || $params['sorting']): ?>
					<div class="portfilio-top-panel<?php if($params['layout'] == '100%'): ?> fullwidth-block<?php endif; ?>" <?php if ($gap_size && $params['layout'] == '100%'): ?>style="padding-left: <?php echo 2*$gap_size; ?>px; padding-right: <?php echo 2*$gap_size; ?>px;"<?php endif; ?>><div class="portfilio-top-panel-row">
						<div class="portfilio-top-panel-left">
						<?php if($params['with_filter'] && count($terms) > 0): ?>


							<div <?php if(!$params['sorting']): ?> style="text-align: center;"<?php endif; ?>  class="portfolio-filters">
								<a href="#" data-filter="*" class="active all title-h6"><?php echo thegem_build_icon('thegem-icons', 'portfolio-show-all'); ?><span class="light"><?php echo $params['all_text']; ?></span></a>
								<?php foreach($terms as $term) : ?>
									<a href="#" data-filter=".<?php echo $term->slug; ?>" class="title-h6"><?php if(get_option('portfoliosets_' . $term->term_id . '_icon_pack') && get_option('portfoliosets_' . $term->term_id . '_icon')) { echo thegem_build_icon(get_option('portfoliosets_' . $term->term_id . '_icon_pack'),get_option('portfoliosets_' . $term->term_id . '_icon')); } ?><span class="light"><?php echo $term->name; ?></span></a>
								<?php endforeach; ?>
							</div>
							<div class="portfolio-filters-resp">
								<button class="menu-toggle dl-trigger"><?php _e('Portfolio filters', 'thegem'); ?><span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span></button>
								<ul class="dl-menu">
									<li><a href="#" data-filter="*"><?php echo $params['all_text']; ?></a></li>
									<?php foreach($terms as $term) : ?>
										<li><a href="#" data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
						</div>
						<div class="portfilio-top-panel-right">
						<?php if($params['sorting']): ?>
							<div class="portfolio-sorting title-h6">
								<div class="orderby light">
									<label for="" data-value="date"><?php _e('Date', 'thegem') ?></label>
									<a href="javascript:void(0);" class="sorting-switcher" data-current="date"></a>
									<label for="" data-value="name"><?php _e('Name', 'thegem') ?></label>
								</div>
								<div class="portfolio-sorting-sep"></div>
								<div class="order light">
									<label for="" data-value="DESC"><?php _e('DESC', 'thegem') ?></label>
									<a href="javascript:void(0);" class="sorting-switcher" data-current="DESC"></a>
									<label for="" data-value="ASC"><?php _e('ASC', 'thegem') ?></label>
								</div>
							</div>

						<?php endif; ?>
						</div>
					</div></div>
				<?php endif; ?>
				<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
				<div class="row" style="<?php echo $row_styles; ?>">
				<div class="portfolio-set clearfix" data-max-row-height="<?php echo floatval($params['metro_max_row_height']); ?>">
	<?php else: ?>
		<div data-page="<?php echo $page; ?>" data-next-page="<?php echo $next_page; ?>">
	<?php endif; ?>

					<?php $eo_marker = false; while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post(); ?>
						<?php $slugs = wp_get_object_terms($post->ID, 'thegem_portfolios', array('fields' => 'slugs')); ?>
						<?php include(locate_template(array('gem-templates/portfolios/content-portfolio-item-'.$params['layout'].'.php', 'gem-templates/portfolios/content-portfolio-item.php'))); ?>
					<?php $eo_marker = !$eo_marker; endwhile; ?>

	<?php if(!$params['is_ajax']) : ?>
				</div><!-- .portflio-set -->
					</div><!-- .row-->
				<?php if($params['pagination'] == 'normal'): ?>
					<div class="portfolio-navigator gem-pagination">
					</div>
				<?php endif; ?>
				<?php if($params['pagination'] == 'more' && $next_page > 0): ?>
					<div class="portfolio-load-more">
						<div class="inner">
							<?php thegem_button(array_merge($params['button'], array('tag' => 'button')), 1); ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if($params['pagination'] == 'scroll' && $next_page > 0): ?>
					<div class="portfolio-scroll-pagination"></div>
				<?php endif; ?>
			</div><!-- .full-width -->
		</div><!-- .portfolio-->
	</div><!-- .portfolio-preloader-wrapper-->
	<?php else: ?>
	</div>
	<?php endif; ?>

<?php else: ?>
	<div data-page="<?php echo esc_attr($page); ?>" data-next-page="<?php echo esc_attr($next_page); ?>"></div>
<?php endif; ?>

<?php $post = $portfolio_posttemp; wp_reset_postdata(); ?>
<?php
}

function thegem_portfolio_block($params = array()) {
	echo '<div class="block-content clearfix">';
	thegem_portfolio_slider($params);
	echo '</div>';
}

// Print Portfolio Slider
function thegem_portfolio_slider($params) {
	$params = array_merge(
		array(
			'portfolio' => '',
			'title' => '',
			'layout' => '3x',
			'no_gaps' => false,
			'display_titles' => 'page',
			'hover' => '',
			'show_info' => false,
			'style' => 'justified',
			'is_slider' => true,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'effects_enabled' => false,
			'background_style' => '',
			'title_style' => 'light',
			'animation' => 'dynamic',
			'autoscroll' => false,
			'gaps_size' => 42,
		),
		$params
	);

	$gap_size = round(intval($params['gaps_size'])/2);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	wp_enqueue_style('thegem-portfolio');


	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('jquery-transform');
	wp_enqueue_script('thegem-juraSlider');
	wp_enqueue_script('thegem-portfolio');

	$layout_columns_count = -1;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;

	$layout_fullwidth = false;
	if($params['layout'] == '100%')
		$layout_fullwidth = true;

	$portfolio_loop = new WP_Query(array(
		'post_type' => 'thegem_pf_item',
		'tax_query' =>$params['portfolio'] ? array(
			array(
				'taxonomy' => 'thegem_portfolios',
				'field' => 'slug',
				'terms' => explode(',', $params['portfolio'])
			)
		) : array(),
		'post_status' => 'publish',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	));

	$terms = array();

	$portfolio_title = __('Portfolio', 'thegem');
	if($portfolio_set = get_term_by('slug', $params['portfolio'], 'thegem_portfolios')) {
		$portfolio_title = $portfolio_set->name;
	}
	$portfolio_title = $params['title'] ? $params['title'] : $portfolio_title;
	global $post;
	$portfolio_posttemp = $post;

	$classes = array('portfolio', 'portfolio-slider', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
	if($layout_fullwidth)
		$classes[] = 'full';
	if( ($params['display_titles'] == 'hover' && $params['layout'] != '1x') || $params['hover'] == 'gradient' || $params['hover'] == 'circular' )
		$classes[] = 'hover-title';
	if ($params['display_titles'] == 'page' && $params['hover'] == 'gradient')
		$classes[] = 'hover-gradient-title';
	if ($params['display_titles'] == 'page' && $params['hover'] == 'circular')
		$classes[] = 'hover-circular-title';
	if($params['style'] == 'masonry')
		$classes[] = 'portfolio-items-masonry';
	if($layout_columns_count != -1)
		$classes[] = 'columns-'.$layout_columns_count;
	if($params['no_gaps'])
		$classes[] = 'without-padding';
	if($params['layout'] == '100%')
		$classes[] = 'fullwidth-columns-'.$params['fullwidth_columns'];

	if ($params['effects_enabled'])
		$classes[] = 'lazy-loading';

	if ($params['disable_socials'])
		$classes[] = 'disable-socials';
	if ($params['portfolio_arrow'])
		$classes[] = $params['portfolio_arrow'];
	if ($params['background_style'])
		$classes[] = 'background-style-'.$params['background_style'];
	if ($params['title_style'])
		$classes[] = 'title-style-'.$params['title_style'];

	$classes[] = 'title-on-' . $params['display_titles'];
	$classes[] = 'gem-slider-animation-' . $params['animation'];


?>

	<?php if($portfolio_loop->have_posts()) : ?>
	<div class="preloader"><div class="preloader-spin"></div></div>
	<div <?php post_class($classes); ?> <?php if($params['effects_enabled']): ?>data-ll-item-delay="0"<?php endif;?> data-hover="<?php echo esc_attr($params['hover']); ?>">
		<div class="navigation <?php if($layout_fullwidth): ?>fullwidth-block<?php endif; ?>">
			<?php if($params['title']): ?>
				<h3 class="title <?php if($params['effects_enabled']): ?>lazy-loading-item<?php endif;?>" <?php if($params['effects_enabled']): ?>data-ll-effect="fading"<?php endif;?>><?php echo $params['title']; ?></h3>
			<?php endif; ?>
			<div class="portolio-slider-prev">
				<span>&#xe603;</span>
			</div>

			<div class="portolio-slider-next">
				<span>&#xe601;</span>
			</div>

			<?php
				if($params['portfolio']) {
					$terms = explode(',', $params['portfolio']);
					foreach($terms as $key => $term) {
						$terms[$key] = get_term_by('slug', $term, 'thegem_portfolios');
						if(!$terms[$key]) {
							unset($terms[$key]);
						}
					}
				} else {
					$terms = get_terms('thegem_portfolios', array('hide_empty' => false));
				}
				$thegem_terms_set = array();
				foreach ($terms as $term)
					$thegem_terms_set[$term->slug] = $term;
			?>

			<div class="portolio-slider-content">
				<div class="portolio-slider-center">
					<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block<?php endif; ?>">
						<div style="margin: -<?php echo $gap_size; ?>px;">
							<div class="portfolio-set clearfix" <?php if(intval($params['autoscroll'])) { echo 'data-autoscroll="'.intval($params['autoscroll']).'"'; } ?>>
								<?php while ($portfolio_loop->have_posts()) : $portfolio_loop->the_post(); ?>
									<?php $slugs = wp_get_object_terms($post->ID, 'thegem_portfolios', array('fields' => 'slugs')); ?>
									<?php include(locate_template('gem-templates/portfolios/content-portfolio-carusel-item.php')); ?>
								<?php endwhile; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php $post = $portfolio_posttemp; wp_reset_postdata(); ?>
<?php
}

// Print Gallery Block
function thegem_gallery_block($params) {
	$params = array_merge(
		array(
			'ids' => array(),
			'gallery' => '',
			'type' => 'slider',
			'layout' => '3x',
			'fullwidth_columns' => '5',
			'style' => 'justified',
			'no_gaps' => false,
			'hover' => 'default',
			'item_style' => '',
			'title' => '',
			'gaps_size' => '',
			'effects_enabled' => '',
			'loading_animation' => 'move-up',
			'metro_max_row_height' => 380
		),
		$params
	);
	wp_enqueue_style('thegem-gallery');
	wp_enqueue_style('thegem-animations');
	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('isotope-js');
	wp_enqueue_script('thegem-isotope-metro');
	wp_enqueue_script('thegem-isotope-masonry-custom');
	wp_enqueue_script('jquery-transform');
	wp_enqueue_script('thegem-removewhitespace');
	wp_enqueue_script('thegem-scroll-monitor');
	wp_enqueue_script('thegem-items-animations');
	wp_enqueue_script('jquery-collagePlus');
	wp_enqueue_script('thegem-gallery');

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');

	$layout_columns_count = -1;
	if($params['layout'] == '2x')
		$layout_columns_count = 2;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;
	if($params['layout'] == '4x')
		$layout_columns_count = 4;

	if(!empty($params['ids'])) {
		$thegem_gallery_images_ids = $params['ids'];
	} else {
		if(metadata_exists('post', $params['gallery'], 'thegem_gallery_images')) {
			$thegem_gallery_images_ids = get_post_meta($params['gallery'], 'thegem_gallery_images', true);
		} else {
			$attachments_ids = get_posts('post_parent=' . $params['gallery'] . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&post_status=publish');
			$thegem_gallery_images_ids = implode(',', $attachments_ids);
		}
		$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));
	}
	$attachments_ids = array_filter(explode(',', $thegem_gallery_images_ids));
	$gallery_uid = uniqid();

?>



<div class="preloader"><div class="preloader-spin"></div></div>
<div class="gallery-preloader-wrapper">
	<?php if($params['title']): ?>
		<h3 style="text-align: center;"><?php echo $params['title']; ?></h3>
	<?php endif; ?>
	<?php if(count($attachments_ids) > 0) : ?>
	<div class="row"
		<?php if ($params['gaps_size'] && ($params['style'])  == 'metro'):;?>style="margin-top: -<?php echo ($params['gaps_size'] / 2) ;?>px"<?php endif;?>
		<?php if ($params['gaps_size'] && ($params['style'])  != 'metro'):;?>style="margin-top: -<?php echo ($params['gaps_size'] / 2) ;?>px"<?php endif;?>>


		<div class="<?php if ($params['gaps_size'] && ($params['style'])  != 'metro'):;?>gaps-margin<?php endif;?><?php if ($params['gaps_size'] && ($params['style'])  == 'metro'):;?>without-padding <?php endif;?> <?php if($params['style'] == 'metro' && $params['item_style']): ?>metro-item-style-<?php echo $params['item_style']; endif; ?> gallery-style-<?php echo $params['style']; ?> gem-gallery-grid <?php if($params['style'] == 'metro'): ?>metro<?php endif; ?> <?php if($params['type'] == 'slider'): ?>gallery-slider<?php endif; ?> col-lg-12 col-md-12 col-sm-12 <?php if($params['type'] == 'grid' && $params['style'] == 'masonry'): ?>gallery-items-masonry<?php endif; ?> <?php if($params['type'] == 'grid' && $params['no_gaps']): ?>without-padding<?php endif; ?> <?php if($layout_columns_count != -1) echo 'columns-'.$layout_columns_count; ?> hover-<?php echo $params['hover']; ?> item-animation-<?php echo $params['loading_animation']; ?> <?php if($params['layout'] == '100%'): ?>fullwidth-columns-<?php echo intval($params['fullwidth_columns']); ?><?php endif; ?>" data-hover="<?php echo $params['hover']; ?>">

			<?php if ($params['type'] == 'grid' && $params['layout'] == '100%'):?>
				<div class="fullwidth-block" <?php if ($params['gaps_size']):;?> style="padding-left: <?php echo ($params['gaps_size'] / 2);?>px; padding-right: <?php echo ($params['gaps_size'] / 2);?>px;"<?php endif;?>>
			<?php endif;?>

				<ul
					<?php if ($params['type'] != 'grid' || $params['layout'] != '100%'):?>
						style="margin-left: -<?php echo ($params['gaps_size'] / 2);?>px; margin-right: -<?php echo ($params['gaps_size'] / 2);?>px;"
					<?php endif;?>

					class="gallery-set clearfix" data-max-row-height="<?php echo floatval($params['metro_max_row_height']); ?>">
					<?php foreach($attachments_ids as $attachment_id) : ?>
						<?php include(locate_template('content-gallery-item.php')); ?>
					<?php endforeach; ?>
				</ul>

			<?php if ($params['type'] == 'grid' && $params['layout'] == '100%'):?>
				</div>
			<?php endif; ?>
		</div>
	</div>



	<?php endif; ?>
</div>
<?php
}

function thegem_news_block($params) {
	echo '<div class="block-content"><div class="container">';
	thegem_newss($params);
	echo '</div></div>';
}

function thegem_newss($params) {
	$params = array_merge(array('news_set' => '', 'effects_enabled' => false), $params);
	$args = array(
		'post_type' => 'thegem_news',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['news_set'] != '') {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_news_sets',
				'field' => 'slug',
				'terms' => explode(',', $params['news_set'])
			)
		);
	}

	$news_items = new WP_Query($args);
	if($news_items->have_posts()) {
		wp_enqueue_script('thegem-news-carousel');
		echo '<div class="preloader"><div class="preloader-spin"></div></div>';
		echo '<div class="gem-news gem-news-type-carousel clearfix">';
		while($news_items->have_posts()) {
			$news_items->the_post();
			include(locate_template('content-news-carousel-item.php'));
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

function thegem_nivoslider($params = array()) {
	$params = array_merge(array('slideshow' => ''), $params);
	$args = array(
		'post_type' => 'thegem_slide',
		'orderby' => 'menu_order ID',
		'order' => 'ASC',
		'posts_per_page' => -1,
	);
	if($params['slideshow']) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'thegem_slideshows',
				'field' => 'slug',
				'terms' => explode(',', $params['slideshow'])
			)
		);
	}
	$slides = new WP_Query($args);

	if($slides->have_posts()) {

		wp_enqueue_style('nivo-slider');
		wp_enqueue_script('thegem-nivoslider-init-script');

		echo '<div class="preloader"><div class="preloader-spin"></div></div>';
		echo '<div class="gem-nivoslider">';
		while($slides->have_posts()) {
			$slides->the_post();
			if(has_post_thumbnail()) {
				$item_data = thegem_get_sanitize_slide_data(get_the_ID());
?>
	<?php if($item_data['link']) : ?>
		<a href="<?php echo esc_url($item_data['link']); ?>" target="<?php echo esc_attr($item_data['link_target']); ?>" class="gem-nivoslider-slide">
	<?php else : ?>
		<div class="gem-nivoslider-slide">
	<?php endif; ?>
	<?php thegem_post_thumbnail('full', false, ''); ?>
	<?php if($item_data['text_position']) : ?>
		<div class="gem-nivoslider-caption" style="display: none;">
			<div class="caption-<?php echo esc_attr($item_data['text_position']); ?>">
				<div class="gem-nivoslider-title"><?php the_title(); ?></div>
				<div class="clearboth"></div>
				<div class="gem-nivoslider-description"><?php the_excerpt(); ?></div>
			</div>
		</div>
	<?php endif; ?>
	<?php if($item_data['link']) : ?>
		</a>
	<?php else : ?>
		</div>
	<?php endif; ?>
<?php
			}
		}
		echo '</div>';
	}
	wp_reset_postdata();
}

function thegem_atts_product_category_grid($out, $pairs, $atts, $shortcode) {
	if (isset($atts['thegem_grid_params'])) {
		$out['thegem_grid_params'] = unserialize(htmlspecialchars_decode($atts['thegem_grid_params']));
	}
	return $out;
}
add_filter('shortcode_atts_product_category', 'thegem_atts_product_category_grid', 10, 4);

function thegem_query_product_category_grid($query_args, $atts, $loop_name) {
	if (($loop_name == 'product_cat' || $loop_name == 'product_category') && isset($atts['thegem_grid_params'])) {
		$query_args['orderby'] = $atts['thegem_grid_params']['orderby'];
		$query_args['order'] = $atts['thegem_grid_params']['order'];

		if ($atts['thegem_grid_params']['pagination'] == 'more' || $atts['thegem_grid_params']['pagination'] == 'scroll') {
			$query_args['paged'] = $atts['thegem_grid_params']['grid_page'];
			$query_args['no_found_rows'] = false;
		} else {
			$query_args['posts_per_page'] = -1;
		}
	}
	return $query_args;
}
add_filter('woocommerce_shortcode_products_query', 'thegem_query_product_category_grid', 10, 3);

function thegem_product_category_grid_before_loop($atts) {
	if (isset($GLOBALS['thegem_grid_params'])) {
		unset($GLOBALS['thegem_grid_params']);
	}
	if (!isset($atts['thegem_grid_params'])) {
		return;
	}
	$GLOBALS['thegem_grid_params'] = $atts['thegem_grid_params'];
}
add_action('woocommerce_shortcode_before_product_cat_loop', 'thegem_product_category_grid_before_loop');
add_action('woocommerce_shortcode_before_product_category_loop', 'thegem_product_category_grid_before_loop');

function thegem_product_category_grid_loop_start($wp_query) {
	if (!isset($GLOBALS['thegem_grid_params'])) {
		return;
	}
	$params = $GLOBALS['thegem_grid_params'];

	$terms = explode(',', $params['categories']);
	foreach($terms as $key => $term) {
		$terms[$key] = get_term_by('slug', $term, 'product_cat');
		if(!$terms[$key]) {
			unset($terms[$key]);
		}
	}

	$thegem_terms_set = array();
	foreach ($terms as $term) {
		$thegem_terms_set[$term->slug] = $term;
	}

	$gap_size = round(intval($params['gaps_size'])/2);

	$next_page = 0;
	if ($wp_query->max_num_pages > $params['grid_page']) {
		$next_page = $params['grid_page'] + 1;
	} else {
		$next_page = 0;
	}
	$GLOBALS['thegem_grid_params']['next_page'] = $next_page;
?>

	<?php if(!$params['is_ajax']) : ?>
		<?php echo apply_filters('portfolio_preloader_filter', '<div class="preloader"><div class="preloader-spin"></div></div>'); ?>
		<div class="portfolio-preloader-wrapper">
		<?php if($params['title']): ?>
			<h3 class="title portfolio-title"><?php echo $params['title']; ?></h3>
		<?php endif; ?>

		<?php

			$portfolio_classes = array(
				'portfolio',
				'products-grid',
				'products',
				'no-padding',
				'portfolio-pagination-' . $params['pagination'],
				'portfolio-style-' . $params['style'],
				'background-style-' . $params['background_style'],
				'title-style-' . $params['title_style'],
				'hover-' . esc_attr($params['hover']),
				'item-animation-' . $params['loading_animation'],
				'title-on-' . $params['display_titles']
			);

			if ($params['layout_columns'] == '1x') {
				$portfolio_classes[] = 'caption-position-' . $params['caption_position'];
			}

			if ($gap_size == 0) {
				$portfolio_classes[] = 'no-gaps';
			}

			if ($params['layout'] == '100%') {
				$portfolio_classes[] = 'fullwidth-columns-' . intval($params['fullwidth_columns']);
			}

			if ($params['display_titles'] == 'page' && $params['hover'] == 'gradient') {
				$portfolio_classes[] = 'hover-gradient-title';
			}

			if ($params['display_titles'] == 'page' && $params['hover'] == 'circular') {
				$portfolio_classes[] = 'hover-circular-title';
			}

			if ($params['display_titles'] == 'hover') {
				$portfolio_classes[] = 'hover-title';
			}

			if ($params['style'] == 'masonry' && $params['layout'] != '1x') {
				$portfolio_classes[] = 'portfolio-items-masonry';
			}

			if ($params['layout_columns'] != -1) {
				$portfolio_classes[] = 'columns-' . intval($params['layout_columns']);
			}

			if ( $params['item_separator'] && ( $params['display_titles'] == 'hover' || ($params['display_titles'] == 'page' && ( $params['hover'] == 'gradient' || $params['hover'] == 'circular' ) ) ) ) {
				$portfolio_classes[] = 'item-separator';
			}

			if ($params['disable_socials']) {
				$portfolio_classes[] = 'portfolio-disable-socials';
			}

			$portfolio_classes = apply_filters('portfolio_classes_filter', $portfolio_classes);

			$row_styles = '';
			if ($params['layout'] == '100%') {
				$row_styles .= 'margin: 0;';
				if ($gap_size) {
					if (thegem_get_option('page_padding_left')) {
						$row_styles .= 'margin-left: -' . $gap_size . 'px;';
					} else {
						$row_styles .= 'padding-left: ' . $gap_size . 'px;';
					}

					if (thegem_get_option('page_padding_right')) {
						$row_styles .= 'margin-right: -' . $gap_size . 'px;';
					} else {
						$row_styles .= 'padding-right: ' . $gap_size . 'px;';
					}
				}
			} else {
				if ($gap_size) {
					$row_styles .= 'margin: -' . $gap_size . 'px;';
				} else {
					$row_styles .= 'margin: 0;';
				}
			}
		?>

			<div data-per-page="<?php echo $params['items_per_page']; ?>" data-portfolio-uid="<?php echo esc_attr($params['portfolio_uid']); ?>" class="<?php echo implode(' ', $portfolio_classes); ?>" data-hover="<?php echo $params['hover']; ?>" <?php if($params['pagination'] == 'more' || $params['pagination'] == 'scroll'): ?>data-next-page="<?php echo esc_attr($next_page); ?>"<?php endif; ?>>
				<?php if(($params['with_filter'] && count($terms) > 0) || $params['sorting']): ?>
					<div class="portfilio-top-panel<?php if($params['layout'] == '100%'): ?> fullwidth-block<?php endif; ?>" <?php if ($gap_size && $params['layout'] == '100%'): ?>style="padding-left: <?php echo 2*$gap_size; ?>px; padding-right: <?php echo 2*$gap_size; ?>px;"<?php endif; ?>><div class="portfilio-top-panel-row">
						<div class="portfilio-top-panel-left">
						<?php if($params['with_filter'] && count($terms) > 0): ?>


							<div <?php if(!$params['sorting']): ?> style="text-align: center;"<?php endif; ?>  class="portfolio-filters">
								<a href="#" data-filter="*" class="active all title-h6"><?php echo thegem_build_icon('thegem-icons', 'portfolio-show-all'); ?><span class="light"><?php echo apply_filters('portfolio_show_all_filter', __('All', 'thegem')); ?></span></a>
								<?php foreach($terms as $term) : ?>
									<a href="#" data-filter=".<?php echo $term->slug; ?>" class="title-h6"><span class="light"><?php echo $term->name; ?></span></a>
								<?php endforeach; ?>
							</div>
							<div class="portfolio-filters-resp">
								<button class="menu-toggle dl-trigger"><?php _e('Portfolio filters', 'thegem'); ?><span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span></button>
								<ul class="dl-menu">
									<li><a href="#" data-filter="*"></span><?php _e('Show All', 'thegem'); ?></a></li>
									<?php foreach($terms as $term) : ?>
										<li><a href="#" data-filter=".<?php echo esc_attr($term->slug); ?>"><?php echo $term->name; ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
						</div>
						<div class="portfilio-top-panel-right">
						<?php if($params['sorting']): ?>
							<div class="portfolio-sorting title-h6">
								<div class="orderby light">
									<label for="" data-value="date"><?php _e('Date', 'thegem') ?></label>
									<a href="javascript:void(0);" class="sorting-switcher" data-current="date"></a>
									<label for="" data-value="name"><?php _e('Name', 'thegem') ?></label>
								</div>
								<div class="portfolio-sorting-sep"></div>
								<div class="order light">
									<label for="" data-value="DESC"><?php _e('DESC', 'thegem') ?></label>
									<a href="javascript:void(0);" class="sorting-switcher" data-current="DESC"></a>
									<label for="" data-value="ASC"><?php _e('ASC', 'thegem') ?></label>
								</div>
							</div>

						<?php endif; ?>
						</div>
					</div></div>
				<?php endif; ?>
				<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block no-paddings<?php endif; ?>">
				<div class="row" style="<?php echo $row_styles; ?>">
				<div class="portfolio-set clearfix" data-max-row-height="<?php echo floatval($params['metro_max_row_height']); ?>">
	<?php else: ?>
		<div data-page="<?php echo $params['grid_page']; ?>" data-next-page="<?php echo $next_page; ?>">
	<?php endif; ?>

<?php
}
add_action('loop_start', 'thegem_product_category_grid_loop_start');
add_action('thegem_products_loop_start', 'thegem_product_category_grid_loop_start');

function thegem_product_category_grid_after_loop($atts) {
	if (!isset($atts['thegem_grid_params']) || !isset($GLOBALS['thegem_grid_params'])) {
		return;
	}
	$params = $GLOBALS['thegem_grid_params'];
	$next_page = $params['next_page'];
	unset($GLOBALS['thegem_grid_params']);
?>
	<?php if(!$params['is_ajax']) : ?>
				</div><!-- .portflio-set -->
					</div><!-- .row-->
				<?php if($params['pagination'] == 'normal'): ?>
					<div class="portfolio-navigator gem-pagination">
					</div>
				<?php endif; ?>
				<?php if($params['pagination'] == 'more' && $next_page > 0): ?>
					<div class="portfolio-load-more">
						<div class="inner">
							<?php thegem_button(array_merge($params['button'], array('tag' => 'button')), 1); ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if($params['pagination'] == 'scroll' && $next_page > 0): ?>
					<div class="portfolio-scroll-pagination"></div>
				<?php endif; ?>
			</div><!-- .full-width -->
		</div><!-- .portfolio-->
	</div><!-- .portfolio-preloader-wrapper-->
	<?php else: ?>
	</div>
	<?php endif; ?>
<?php
}
add_action('woocommerce_shortcode_after_product_cat_loop', 'thegem_product_category_grid_after_loop');
add_action('woocommerce_shortcode_after_product_category_loop', 'thegem_product_category_grid_after_loop');

// Print Products Grid
function thegem_products_grid($params) {
	$params = array_merge(
		array(
			'categories' => '',
			'title' => '',
			'layout' => '3x',
			'layout_version' => 'fullwidth',
			'caption_position' => 'right',
			'style' => 'justified',
			'gaps_size' => 42,
			'display_titles' => 'page',
			'background_style' => 'white',
			'title_style' => 'light',
			'hover' => '',
			'pagination' => 'normal',
			'loading_animation' => 'move-up',
			'items_per_page' => 8,
			'with_filter' => false,
			'is_ajax' => false,
			'item_separator' => false,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'sorting' => false,
			'orderby' => '',
			'order' => '',
			'button' => array(),
			'metro_max_row_height' => 380
		),
		$params
	);

	$params['button'] = array_merge(array(
		'text' => __('Load More', 'thegem'),
		'style' => 'flat',
		'size' => 'medium',
		'text_weight' => 'normal',
		'no_uppercase' => 0,
		'corner' => 25,
		'border' => 2,
		'text_color' => '',
		'background_color' => '#00bcd5',
		'border_color' => '',
		'hover_text_color' => '',
		'hover_background_color' => '',
		'hover_border_color' => '',
		'icon_pack' => 'elegant',
		'icon_elegant' => '',
		'icon_material' => '',
		'icon_fontawesome' => '',
		'icon_userpack' => '',
		'icon_position' => 'left',
		'separator' => 'load-more',
	), $params['button']);

	$params['button']['icon'] = '';
	if($params['button']['icon_elegant'] && $params['button']['icon_pack'] == 'elegant') {
		$params['button']['icon'] = $params['button']['icon_elegant'];
	}
	if($params['button']['icon_material'] && $params['button']['icon_pack'] == 'material') {
		$params['button']['icon'] = $params['button']['icon_material'];
	}
	if($params['button']['icon_fontawesome'] && $params['button']['icon_pack'] == 'fontawesome') {
		$params['button']['icon'] = $params['button']['icon_fontawesome'];
	}
	if($params['button']['icon_userpack'] && $params['button']['icon_pack'] == 'userpack') {
		$params['button']['icon'] = $params['button']['icon_userpack'];
	}

	$params['loading_animation'] = thegem_check_array_value(array('disabled', 'bounce', 'move-up', 'fade-in', 'fall-perspective', 'scale', 'flip'), $params['loading_animation'], 'move-up');

	if (empty($params['fullwidth_columns'])) {
		$params['fullwidth_columns'] = 5;
	}

	if ($params['sorting'] == 'false') {
		$params['sorting'] = false;
	}

	if ($params['sorting'] == 'true') {
		$params['sorting'] = true;
	}

	$params['orderby'] = $params['orderby'] ? $params['orderby'] : ($params['sorting'] ? 'date' :'menu_order');
	$params['order'] = $params['order'] ? $params['order'] : ($params['sorting'] ? 'DESC' :'ASC');

	switch ($params['layout']) {
		case '1x':
			$params['layout_columns'] = 1;
			break;
		case '2x':
			$params['layout_columns'] = 2;
			break;
		case '3x':
			$params['layout_columns'] = 3;
			break;
		case '4x':
			$params['layout_columns'] = 4;
			break;
		default:
			$params['layout_columns'] = -1;
	}

	$params['items_per_page'] = intval($params['items_per_page']) ? intval($params['items_per_page']) : 8;
	$params['grid_page'] = 1;

	if ($params['pagination'] == 'more' || $params['pagination'] == 'scroll') {
		if(isset($params['more_count'])) {
			$params['items_per_page'] = intval($params['more_count']);
		}
		if($params['layout_columns'] == -1) {
			$params['layout_columns'] = 5;
		}
		if($params['items_per_page'] == 0) {
			$params['items_per_page'] = $params['layout_columns'] * 2;
		}
		$params['grid_page'] = isset($params['more_page']) ? intval($params['more_page']) : 1;
		if ($params['grid_page'] == 0) {
			$params['grid_page'] = 1;
		}
	}

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_style('thegem-animations');
	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('isotope-js');
	wp_enqueue_script('thegem-isotope-metro');
	wp_enqueue_script('thegem-isotope-masonry-custom');
	wp_enqueue_script('thegem-scroll-monitor');
	wp_enqueue_script('jquery-transform');
	wp_enqueue_script('thegem-items-animations');
	wp_enqueue_script('thegem-portfolio');
	wp_enqueue_script('thegem-woocommerce');

	$params['portfolio_uid'] = substr( md5(rand()), 0, 7);
	$localize = array_merge(
		array('data' => $params, 'action' => 'product_grid_load_more'),
		array(
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('product_grid_ajax-nonce')
		)
	);
	wp_localize_script('thegem-portfolio', 'portfolio_ajax_'.$params['portfolio_uid'], $localize);

	echo do_shortcode('[product_category_gem per_page="' . $params['items_per_page'] . '" columns="' . $params['layout_columns'] . '" orderby="' . $params['orderby'] . '" category="' . $params['categories'] . '" order="' . $params['order'] . '" thegem_grid_params="' . htmlspecialchars(serialize($params)) . '"]');
}

if(!function_exists('thegem_video_background')) {
function thegem_video_background($video_type, $video, $aspect_ratio = '16:9', $headerUp = false, $color = '', $opacity = '', $poster='') {
	$output = '';
	$video_type = thegem_check_array_value(array('', 'youtube', 'vimeo', 'self'), $video_type, '');
	if($video_type && $video) {
		$video_block = '';
		if($video_type == 'youtube' || $video_type == 'vimeo') {
			$link = '';
			if($video_type == 'youtube') {
				$link = '//www.youtube.com/embed/'.$video.'?playlist='.$video.'&autoplay=1&mute=1&controls=0&loop=1&fs=0&showinfo=0&autohide=1&iv_load_policy=3&rel=0&disablekb=1&wmode=transparent';
			}
			if($video_type == 'vimeo') {
				$link = '//player.vimeo.com/video/'.$video.'?autoplay=1&muted=1&controls=0&loop=1&title=0&badge=0&byline=0&autopause=0';
			}
			$video_block = '<iframe class="gem-video-background-iframe" src="'.esc_url($link).'" frameborder="0" muted="muted"></iframe>';
		} else {
			$video_block = '<video autoplay="autoplay" loop="loop" src="'.$video.'" muted="muted"'.($poster ? ' poster="'.esc_url($poster).'"' : '').'></video>';
		}
		$overlay_css = '';
		if($color) {
			$overlay_css = 'background-color: '.$color.'; opacity: '.floatval($opacity).';';
		}
		$output = '<div class="gem-video-background" data-aspect-ratio="'.esc_attr($aspect_ratio).'"'.($headerUp ? ' data-headerup="1"' : '').'><div class="gem-video-background-inner">'.$video_block.'</div><div class="gem-video-background-overlay" style="'.$overlay_css.'"></div></div>';
	}
	return $output;
}
}

/* Acoordion Script Reaplace */

function thegem_vc_base_register_front_js() {
	wp_deregister_script('vc_accordion_script');
	wp_register_script('vc_accordion_script', get_template_directory_uri() . '/js/vc-accordion.js', array('jquery'), WPB_VC_VERSION, true);
	wp_register_script('thegem_tabs_script', get_template_directory_uri() . '/js/vc-tabs.min.js', array('jquery', 'vc_accordion_script'), WPB_VC_VERSION, true);
	wp_register_style( 'vc_tta_style', vc_asset_url( 'css/js_composer_tta.min.css' ), false, WPB_VC_VERSION );
}
add_action('vc_base_register_front_js', 'thegem_vc_base_register_front_js');

function thegem_instagram_gallery($params) {
	$params = array_merge(
		array(
			'instagram' => '',
			'title' => '',
			'layout' => '3x',
			'fullwidth_columns' => 5,
			'no_gaps' => '',
			'autoscroll' => false,
			'hover' => 'default',
			'effects_enabled' => false,
			'gaps_size' => 42,
			'arrow' => 'portfolio_slider_arrow_small',
		),$params);

	$gap_size = round(intval($params['gaps_size'])/2);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	wp_enqueue_style('thegem-portfolio');

	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('jquery-transform');
	wp_enqueue_script('thegem-juraSlider');
	wp_enqueue_script('thegem-portfolio');

	$layout_columns_count = -1;
	if($params['layout'] == '3x')
		$layout_columns_count = 3;

	$layout_fullwidth = false;
	if($params['layout'] == '100%')
		$layout_fullwidth = true;

	$images = thegem_scrape_instagram($params['instagram']);

	$classes = array('gem-instagram-gallery', 'portfolio', 'portfolio-slider', 'hover-title', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
	if($layout_fullwidth)
		$classes[] = 'full';
	if($params['no_gaps'])
		$classes[] = 'without-padding';
	if($params['layout'] == '100%')
		$classes[] = 'fullwidth-columns-'.$params['fullwidth_columns'];

	if ($params['effects_enabled'])
		$classes[] = 'lazy-loading';

	if ($params['arrow'])
		$classes[] = $params['arrow'];
	$classes[] = 'gem-slider-animation-dynamic';
?>

	<?php if(is_array($images) && count($images)) : ?>
	<div class="preloader"><div class="preloader-spin"></div></div>
	<div <?php post_class($classes); ?> <?php if($params['effects_enabled']): ?>data-ll-item-delay="0"<?php endif;?> data-hover="<?php echo esc_attr($params['hover']); ?>">
		<div class="navigation <?php if($layout_fullwidth): ?>fullwidth-block<?php endif; ?>">
			<?php if($params['title']): ?>
				<h3 class="title <?php if($params['effects_enabled']): ?>lazy-loading-item<?php endif;?>" <?php if($params['effects_enabled']): ?>data-ll-effect="fading"<?php endif;?>><?php echo $params['title']; ?></h3>
			<?php endif; ?>
			<div class="portolio-slider-prev">
				<span>&#xe603;</span>
			</div>

			<div class="portolio-slider-next">
				<span>&#xe601;</span>
			</div>

			<div class="portolio-slider-content">
				<div class="portolio-slider-center">
					<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block<?php endif; ?>">
						<div class="portfolio-set clearfix" style="margin: -<?php echo $gap_size; ?>px;" <?php if(intval($params['autoscroll'])) { echo 'data-autoscroll="'.intval($params['autoscroll']).'"'; } ?>>
							<?php foreach ($images as $i_image) : ?>
								<?php include(locate_template('content-instagram-gallery-carousel-item.php')); ?>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>
<?php
}

function thegem_scrape_instagram( $username ) {

	$username = strtolower( $username );
	$username = str_replace( '@', '', $username );

	if ( false === ( $instagram = get_transient( 'instagram-a5-'.sanitize_title_with_dashes( $username ) ) ) ) {

		$remote = wp_remote_get( 'https://www.instagram.com/'.trim( $username ));
		if ( is_wp_error( $remote ) )
		return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'thegem' ) );

		if ( 200 != wp_remote_retrieve_response_code( $remote ) )
		return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'thegem' ) );

		$shards = explode( 'window._sharedData = ', $remote['body'] );
		$insta_json = explode( ';</script>', $shards[1] );
		$insta_array = json_decode( $insta_json[0], TRUE );

		if ( ! $insta_array )
		return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'thegem' ) );

		if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
		} else {
		return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'thegem' ) );
		}

		if ( ! is_array( $images ) )
		return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'thegem' ) );

		$instagram = array();

		foreach ( $images as $image_node ) {
		$image = $image_node['node'];
		$image['thumbnail_src'] = preg_replace( '/^https?\:/i', '', $image['thumbnail_src'] );
		$image['display_url'] = preg_replace( '/^https?\:/i', '', $image['display_url'] );

		// handle both types of CDN url
		if ( ( strpos( $image['thumbnail_src'], 's640x640' ) !== false ) ) {
			$image['thumbnail'] = str_replace( 's640x640', 's160x160', $image['thumbnail_src'] );
			$image['small'] = str_replace( 's640x640', 's320x320', $image['thumbnail_src'] );
		} else {
			$urlparts = wp_parse_url( $image['thumbnail_src'] );
			$pathparts = explode( '/', $urlparts['path'] );
			array_splice( $pathparts, 3, 0, array( 's160x160' ) );
			$image['thumbnail'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
			$pathparts[3] = 's320x320';
			$image['small'] = '//' . $urlparts['host'] . implode( '/', $pathparts );
		}

		$image['large'] = $image['thumbnail_src'];

		if ( $image['is_video'] == true ) {
			$type = 'video';
		} else {
			$type = 'image';
		}

		$caption = __( 'Instagram Image', 'thegem' );
		if ( ! empty( $image['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
			$caption = $image['edge_media_to_caption']['edges'][0]['node']['text'];
		}

		$instagram[] = array(
			'description' => $caption,
			'time' => $image['taken_at_timestamp'],
			'comments' => $image['edge_media_to_comment']['count'],
			'likes' => $image['edge_media_preview_like']['count'],
			'thumbnail' => $image['thumbnail'],
			'small' => $image['small'],
			'large' => $image['large'],
			'original' => $image['display_url'],
			'type' => $type
		);
		}

		// do not set an empty transient - should help catch private or empty accounts
		if ( ! empty( $instagram ) ) {
		$instagram = base64_encode( serialize( $instagram ) ); //100% safe - ignore theme check nag
		set_transient( 'instagram-a5-'.sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS*2 ) );
		}
	}

	if ( ! empty( $instagram ) ) {
		return unserialize( base64_decode( $instagram ) );
	} else {
		return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'thegem' ) );
	}
}

// Print Product Slider
function thegem_atts_product_category_slider($out, $pairs, $atts, $shortcode) {
	if (isset($atts['thegem_slider_params'])) {
		$out['thegem_slider_params'] = unserialize(htmlspecialchars_decode($atts['thegem_slider_params']));
	}
	return $out;
}
add_filter('shortcode_atts_product_category', 'thegem_atts_product_category_slider', 10, 4);

function thegem_product_category_slider_before_loop($atts) {
	if (isset($GLOBALS['thegem_slider_params'])) {
		unset($GLOBALS['thegem_slider_params']);
	}
	if (!isset($atts['thegem_slider_params'])) {
		return;
	}
	$GLOBALS['thegem_slider_params'] = $atts['thegem_slider_params'];
}
add_action('woocommerce_shortcode_before_product_cat_loop', 'thegem_product_category_slider_before_loop');
add_action('woocommerce_shortcode_before_product_category_loop', 'thegem_product_category_slider_before_loop');

function thegem_product_category_slider_loop_start($wp_query) {
	if (!isset($GLOBALS['thegem_slider_params'])) {
		return;
	}
	$params = $GLOBALS['thegem_slider_params'];

	$gap_size = round(intval($params['gaps_size'])/2);

	$layout_columns_count = -1;
	if ($params['layout'] == '3x')
		$layout_columns_count = 3;
	if ($params['layout'] == '2x')
		$layout_columns_count = 2;

	$layout_fullwidth = false;
	if ($params['layout'] == '100%')
		$layout_fullwidth = true;

	$classes = array('portfolio', 'portfolio-slider', 'products-slider', 'products', 'clearfix', 'no-padding', 'col-lg-12', 'col-md-12', 'col-sm-12', 'hover-'.$params['hover']);
	if($layout_fullwidth)
		$classes[] = 'full';
	if( ($params['display_titles'] == 'hover' && $params['layout'] != '1x') || $params['hover'] == 'gradient' || $params['hover'] == 'circular' )
		$classes[] = 'hover-title';
	if ($params['display_titles'] == 'page' && $params['hover'] == 'gradient')
		$classes[] = 'hover-gradient-title';
	if ($params['display_titles'] == 'page' && $params['hover'] == 'circular')
		$classes[] = 'hover-circular-title';
	if($layout_columns_count != -1)
		$classes[] = 'columns-'.$layout_columns_count;
	if($params['no_gaps'])
		$classes[] = 'without-padding';
	if($params['layout'] == '100%')
		$classes[] = 'fullwidth-columns-'.$params['fullwidth_columns'];

	$classes[] = 'portfolio-items-' . $params['style'];

	if ($params['effects_enabled'])
		$classes[] = 'lazy-loading';

	if ($params['disable_socials'])
		$classes[] = 'disable-socials';
	if ($params['slider_arrow'])
		$classes[] = $params['slider_arrow'];
	if ($params['background_style'])
		$classes[] = 'background-style-'.$params['background_style'];
	if ($params['title_style'])
		$classes[] = 'title-style-'.$params['title_style'];
	if ( $params['item_separator'] && ( $params['display_titles'] == 'hover' || ($params['display_titles'] == 'page' && ( $params['hover'] == 'gradient' || $params['hover'] == 'circular' ) ) ) ) {
		$classes[] = 'item-separator';
	}
	if ($params['disable_socials']) {
		$classes[] = 'portfolio-disable-socials';
	}

	$classes[] = 'title-on-' . $params['display_titles'];
	$classes[] = 'gem-slider-animation-' . $params['animation'];

	?>

	<div class="preloader"><div class="preloader-spin"></div></div>
	<div <?php post_class($classes); ?> <?php if($params['effects_enabled']): ?>data-ll-item-delay="0"<?php endif;?> data-hover="<?php echo esc_attr($params['hover']); ?>">
		<div class="navigation <?php if($layout_fullwidth): ?>fullwidth-block<?php endif; ?>">
			<?php if($params['title']): ?>
				<h3 class="title <?php if($params['effects_enabled']): ?>lazy-loading-item<?php endif;?>" <?php if($params['effects_enabled']): ?>data-ll-effect="fading"<?php endif;?>><?php echo $params['title']; ?></h3>
			<?php endif; ?>
			<div class="portolio-slider-prev">
				<span>&#xe603;</span>
			</div>

			<div class="portolio-slider-next">
				<span>&#xe601;</span>
			</div>

			<div class="portolio-slider-content">
				<div class="portolio-slider-center">
					<div class="<?php if($params['layout'] == '100%'): ?>fullwidth-block<?php endif; ?>">
						<div style="margin: -<?php echo $gap_size; ?>px;">
							<div class="portfolio-set clearfix" <?php if(intval($params['autoscroll'])) { echo 'data-autoscroll="'.intval($params['autoscroll']).'"'; } ?>>
	<?php
}
add_action('loop_start', 'thegem_product_category_slider_loop_start');
add_action('thegem_products_loop_start', 'thegem_product_category_slider_loop_start');

function thegem_product_category_slider_after_loop($atts) {
	if (!isset($atts['thegem_slider_params']) || !isset($GLOBALS['thegem_slider_params'])) {
		return;
	}
	unset($GLOBALS['thegem_slider_params']);

	?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
add_action('woocommerce_shortcode_after_product_cat_loop', 'thegem_product_category_slider_after_loop');
add_action('woocommerce_shortcode_after_product_category_loop', 'thegem_product_category_slider_after_loop');

function thegem_product_slider($params) {
	$params = array_merge(
		array(
			'categories' => '',
			'title' => '',
			'layout' => '3x',
			'no_gaps' => false,
			'display_titles' => 'page',
			'hover' => '',
			'background_style' => 'white',
			'title_style' => 'light',
			'item_separator' => false,
			'disable_socials' => false,
			'fullwidth_columns' => '5',
			'effects_enabled' => false,
			'gaps_size' => 42,
			'animation' => 'dynamic',
			'autoscroll' => false,
			'slider_arrow' => 'portfolio_slider_arrow_big',
		),
		$params
	);

	if (empty($params['fullwidth_columns']))
		$params['fullwidth_columns'] = 5;

	wp_enqueue_style('thegem-portfolio');
	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('jquery-transform');
	wp_enqueue_script('thegem-juraSlider');
	wp_enqueue_script('thegem-portfolio');
	wp_enqueue_script('thegem-woocommerce');

	echo do_shortcode('[product_category_gem per_page="-1" columns="4" category="' . $params['categories'] . '" thegem_slider_params="' . htmlspecialchars(serialize($params)) . '"]');
}
