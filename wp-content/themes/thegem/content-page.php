<?php
/**
 * The template used for displaying page content on home page
 */

	$thegem_page_data = array(
		'title' => thegem_get_sanitize_page_title_data(get_the_ID()),
		'effects' => thegem_get_sanitize_page_effects_data(get_the_ID()),
		'slideshow' => thegem_get_sanitize_page_slideshow_data(get_the_ID()),
		'sidebar' => thegem_get_sanitize_page_sidebar_data(get_the_ID())
	);
	if($thegem_page_data['effects']['effects_page_scroller']) {
		wp_enqueue_script('thegem-page-scroller');
		$thegem_page_data['effects']['effects_no_bottom_margin'] = true;
		$thegem_page_data['effects']['effects_no_top_margin'] = true;
	}
	$thegem_no_margins_block = '';
	if($thegem_page_data['effects']['effects_no_bottom_margin']) {
		$thegem_no_margins_block .= ' no-bottom-margin';
	}
	if($thegem_page_data['effects']['effects_no_top_margin']) {
		$thegem_no_margins_block .= ' no-top-margin';
	}

	$thegem_panel_classes = array('panel', 'row');
	$thegem_center_classes = 'panel-center';
	$thegem_sidebar_classes = '';
	if(is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar']['sidebar_position']) {
		$thegem_panel_classes[] = 'panel-sidebar-position-'.$thegem_page_data['sidebar']['sidebar_position'];
		$thegem_panel_classes[] = 'with-sidebar';
		$thegem_center_classes .= ' col-lg-9 col-md-9 col-sm-12';
		if($thegem_page_data['sidebar']['sidebar_position'] == 'left') {
			$thegem_center_classes .= ' col-md-push-3 col-sm-push-0';
			$thegem_sidebar_classes .= ' col-md-pull-9 col-sm-pull-0';
		}
	} else {
		$thegem_center_classes .= ' col-xs-12';
	}
	if($thegem_page_data['sidebar']['sidebar_sticky']) {
		$thegem_panel_classes[] = 'panel-sidebar-sticky';
	}
	$thegem_pf_data = array();
	if(get_post_type() == 'thegem_pf_item') {
		$thegem_pf_data = thegem_get_sanitize_pf_item_data(get_the_ID());
	}
	if($thegem_page_data['slideshow']['slideshow_type']) {
		thegem_slideshow_block(array('slideshow_type' => $thegem_page_data['slideshow']['slideshow_type'], 'slideshow' => $thegem_page_data['slideshow']['slideshow_slideshow'], 'lslider' => $thegem_page_data['slideshow']['slideshow_layerslider'], 'slider' => $thegem_page_data['slideshow']['slideshow_revslider']));
	}
	echo thegem_page_title();
?>

<div class="block-content<?php echo esc_attr($thegem_no_margins_block); ?>">
	<div class="container<?php if(get_post_type() == 'thegem_pf_item' && $thegem_pf_data['fullwidth']) { echo '-fullwidth'; } ?>">
		<div class="<?php echo esc_attr(implode(' ', $thegem_panel_classes)); ?>">

			<div class="<?php echo esc_attr($thegem_center_classes); ?>">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="entry-content post-content">
						<?php
							if((get_post_type() == 'post' || get_post_type() == 'thegem_news') && $thegem_featured_content = thegem_get_post_featured_content(get_the_ID(), 'thegem-blog-default', true)) {
								echo '<div class="blog-post-image centered-box">';
								echo $thegem_featured_content;
								echo '</div>';
							}
						?>
						<?php if(get_post_type() == 'post'):
							$thegem_categories = get_the_category();
							$thegem_categories_list = array();
							foreach($thegem_categories as $thegem_category) {
								$thegem_categories_list[] = '<a href="'.esc_url(get_category_link( $thegem_category->term_id )).'" title="'.esc_attr( sprintf( __( "View all posts in %s", "thegem" ), $thegem_category->name ) ).'">'.$thegem_category->cat_name.'</a>';
							}
							$print_block = false;
							ob_start();
						?>

							<div class="post-meta date-color">
								<div class="entry-meta single-post-meta clearfix gem-post-date">
									<div class="post-meta-right">

										<?php if(comments_open() && !thegem_get_option('blog_hide_comments')) : $print_block = true; ?>
											<span class="comments-link"><?php comments_popup_link(0, 1, '%'); ?></span>
										<?php endif; ?>
										<?php if(comments_open() && !thegem_get_option('blog_hide_comments') && function_exists('zilla_likes') && !thegem_get_option('blog_hide_likes')): ?><span class="sep"></span><?php endif; ?>
										<?php if( function_exists('zilla_likes') && !thegem_get_option('blog_hide_likes')) { $print_block = true; echo '<span class="post-meta-likes">';zilla_likes();echo '</span>'; } ?>
										<?php if(!thegem_get_option('blog_hide_navigation')) : $print_block = true; ?>
											<span class="post-meta-navigation">
												<?php previous_post_link('<span class="post-meta-navigation-prev" title="'.esc_attr__('Previous post', 'thegem').'">%link</span>', '&#xe636;'); ?>
												<?php if(!empty($thegem_categories[0])) : ?><span class="post-meta-category-link"><a href="<?php echo esc_url(apply_filters('thegem_blog_category_link', get_category_link($thegem_categories[0]->term_id))); ?>">&#xe620;</a></span><?php endif; ?>
												<?php next_post_link('<span class="post-meta-navigation-next" title="'.esc_attr__('Next post', 'thegem').'">%link</span>', '&#xe634;'); ?>
											</span>
										<?php endif ?>
									</div>
									<div class="post-meta-left">
										<?php if(!thegem_get_option('blog_hide_author')) : $print_block = true; ?>
											<span class="post-meta-author"><?php printf( esc_html__( 'By %s', 'thegem' ), get_the_author_link() ) ?></span>
										<?php endif ?>
										<?php if($thegem_categories && !thegem_get_option('blog_hide_categories')) : $print_block = true; ?>
											<?php if(!thegem_get_option('blog_hide_author')): ?><span class="sep"></span> <?php endif ?><span class="post-meta-categories"><?php echo implode(' <span class="sep"></span> ', $thegem_categories_list); ?></span>
										<?php endif ?>
										<?php if(!thegem_get_option('blog_hide_date')) : $print_block = true; ?>
											<?php if(!thegem_get_option('blog_hide_author') || $thegem_categories && !thegem_get_option('blog_hide_categories')) : ?><span class="sep"></span> <?php endif ?><span class="post-meta-date"><?php the_date(); ?></span>
										<?php endif ?>
									</div>
								</div><!-- .entry-meta -->
							</div>
						<?php
							$post_block_print = ob_get_clean();
							if($print_block) {
								echo $post_block_print;
							}
							endif;
						?>

						<?php if(get_post_type() == 'thegem_pf_item') :
							$thegem_categories = get_the_terms(get_the_ID(), 'thegem_portfolios');
							$thegem_categories_list = array();
							if($thegem_categories) {
								foreach($thegem_categories as $thegem_category) {
									$thegem_categories_list[] = '<span class="gem-date-color">'.$thegem_category->name.'</span>';
								}
							}
						?>

							<div class="post-meta date-color">
								<div class="entry-meta single-post-meta clearfix gem-post-date">
									<div class="post-meta-right">
										<?php if(!thegem_get_option('portfolio_hide_top_navigation')): ?>
											<span class="post-meta-navigation">
												<?php previous_post_link('<span class="post-meta-navigation-prev" title="'.esc_attr__('Previous post', 'thegem').'">%link</span>', '&#xe603;', false, '', 'thegem_portfolios'); ?>
												<?php if($thegem_pf_data['back_url']) : ?><span class="post-meta-category-link"><a href="<?php echo esc_url($thegem_pf_data['back_url']); ?>">&#xe66d;</a></span><?php endif; ?>
												<?php next_post_link('<span class="post-meta-navigation-next" title="'.esc_attr__('Next post', 'thegem').'">%link</span>', '&#xe601;', false, '', 'thegem_portfolios'); ?>
											</span>
										<?php endif ?>
									</div>
									<div class="post-meta-left">
										<?php if(!thegem_get_option('portfolio_hide_date')): ?>
											<span class="post-meta-date"><?php the_date(); ?></span>
										<?php endif ?>
										<?php if($thegem_categories && !thegem_get_option('portfolio_hide_sets')): ?>
											<?php if(!thegem_get_option('portfolio_hide_date')): ?><span class="sep"></span> <?php endif ?><span class="post-meta-categories"><?php echo implode(' <span class="sep"></span> ', $thegem_categories_list); ?></span>
										<?php endif ?>
										<?php if( function_exists('zilla_likes') && !thegem_get_option('portfolio_hide_likes')) { echo '<span class="sep"></span> <span class="post-meta-likes">';zilla_likes();echo '</span>'; } ?>
									</div>
								</div><!-- .entry-meta -->
							</div>
						<?php endif ?>

						<?php
							the_content();
							wp_link_pages( array(
								'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'thegem' ) . '</span>',
								'after'       => '</div>',
								'link_before' => '<span>',
								'link_after'  => '</span>',
							) );
						?>
					</div><!-- .entry-content -->

					<?php if (get_post_type() == 'post') {
						if(!thegem_get_option('blog_hide_tags')) {
							echo get_the_tag_list('<div class="post-tags-list date-color">', '', '</div>');
						}
						if(!thegem_get_option('blog_hide_socials')) {
							thegem_socials_sharing();
						}
					} ?>

					<?php if (get_post_type() == 'post') { thegem_author_info(get_the_ID(), true); } ?>

					<?php if (get_post_type() == 'post' && !thegem_get_option('blog_hide_realted')) { thegem_related_posts(); } ?>

					<?php if (get_post_type() == 'thegem_pf_item' ) : ?>
						<div class="portfolio-item-page-bottom clearfix">
							<?php if(!thegem_get_option('portfolio_hide_socials')) : ?>
								<div class="socials-colored socials-rounded<?php if(get_post_type() == 'thegem_pf_item' && $thegem_pf_data['fullwidth']) { echo ' centered-box'; } ?>">
									<?php thegem_socials_sharing(); ?>
								</div>
							<?php endif; ?>
							<?php if($thegem_pf_data['project_link']) { thegem_button(array('size' => 'tiny', 'href' => $thegem_pf_data['project_link'], 'position' => $thegem_pf_data['fullwidth'] ? 'center' : 'right', 'text' => ($thegem_pf_data['project_text'] ? $thegem_pf_data['project_text'] : __('Go to project', 'thegem')), 'extra_class' => 'project-button'), 1); } ?>
						</div>
						<?php if(!thegem_get_option('portfolio_hide_bottom_navigation')): ?>
							<div class="block-divider gem-default-divider"></div>
							<div class="block-navigation<?php if(get_post_type() == 'thegem_pf_item' && $thegem_pf_data['fullwidth']) { echo ' centered-box'; } ?>">
								<?php if($thegem_nav_post = get_previous_post(false, '', 'thegem_portfolios')) : ?>
									<?php thegem_button(array(
										'text' => __('Prev', 'thegem'),
										'href' => get_permalink($thegem_nav_post->ID),
										'style' => 'outline',
										'size' => 'tiny',
										'position' => $thegem_pf_data['fullwidth'] ? 'inline' : 'left',
										'icon' => 'prev',
										'border_color' => thegem_get_option('button_background_hover_color'),
										'text_color' => thegem_get_option('button_background_hover_color'),
										'hover_background_color' => thegem_get_option('button_background_hover_color'),
										'hover_text_color' => thegem_get_option('button_outline_text_hover_color'),
										'extra_class' => 'block-portfolio-navigation-prev'
									), 1); ?>
								<?php endif; ?>
								<?php if($thegem_nav_post = get_next_post(false, '', 'thegem_portfolios')) : ?>
									<?php thegem_button(array(
										'text' => __('Next', 'thegem'),
										'href' => get_permalink($thegem_nav_post->ID),
										'style' => 'outline',
										'size' => 'tiny',
										'position' => $thegem_pf_data['fullwidth'] ? 'inline' : 'right',
										'icon' => 'next',
										'icon_position' => 'right',
										'border_color' => thegem_get_option('button_background_hover_color'),
										'text_color' => thegem_get_option('button_background_hover_color'),
										'hover_background_color' => thegem_get_option('button_background_hover_color'),
										'hover_text_color' => thegem_get_option('button_outline_text_hover_color'),
										'extra_class' => 'block-portfolio-navigation-next'
									), 1); ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<?php
						if ( comments_open() || get_comments_number() ) {
						comments_template();
						}
					?>

				</article><!-- #post-## -->

			</div>

			<?php
				if(is_active_sidebar('page-sidebar') && $thegem_page_data['sidebar']['sidebar_position']) {
					echo '<div class="sidebar col-lg-3 col-md-3 col-sm-12'.esc_attr($thegem_sidebar_classes).'" role="complementary">';
					get_sidebar('page');
					echo '</div><!-- .sidebar -->';
				}
			?>

		</div>

	</div>
</div><!-- .block-content -->
