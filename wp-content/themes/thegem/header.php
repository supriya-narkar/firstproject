<?php
	$thegem_page_id = is_singular() ? get_the_ID() : 0;
	if(is_404() && get_post(thegem_get_option('404_page'))) {
		$thegem_page_id = thegem_get_option('404_page');
	}
	if((is_post_type_archive('product') || is_tax('product_cat') || is_tax('product_tag')) && function_exists('wc_get_page_id')) {
		$thegem_page_id = wc_get_page_id('shop');
	}
	$thegem_header_params = thegem_get_sanitize_page_header_data($thegem_page_id);
	$thegem_effects_params = thegem_get_sanitize_page_effects_data($thegem_page_id);
	if(is_tax() || is_category() || is_tag()) {
		$thegem_term_id = get_queried_object()->term_id;
		if(get_term_meta($thegem_term_id , 'thegem_taxonomy_custom_page_options', true)) {
			$thegem_header_params = thegem_get_sanitize_page_header_data($thegem_term_id, array(), 'term');
			$thegem_effects_params = thegem_get_sanitize_page_effects_data($thegem_term_id, array(), 'term');
		}
	}
	if($thegem_effects_params['effects_page_scroller']) {
		$thegem_header_params['header_hide_top_area'] = true;
		$thegem_header_params['header_transparent'] = true;
	}
	$thegem_header_light = $thegem_header_params['header_menu_logo_light'] ? '_light' : '';
?>

<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php wp_head(); ?>
</head>

<?php
	$thegem_preloader_data = thegem_get_sanitize_page_preloader_data($thegem_page_id);
	if(is_tax() || is_category() || is_tag()) {
		$thegem_term_id = get_queried_object()->term_id;
		if(get_term_meta($thegem_term_id , 'thegem_taxonomy_custom_page_options', true)) {
			$thegem_preloader_data = thegem_get_sanitize_page_header_data($thegem_term_id, array(), 'term');
		}
	}
?>

<body <?php body_class(); ?>>

<?php do_action('gem_before_page_content'); ?>

<?php if ( thegem_get_option('enable_page_preloader') || ( $thegem_preloader_data && !empty($thegem_preloader_data['enable_page_preloader']) ) ) : ?>
	<div id="page-preloader"><div class="page-preloader-spin"></div></div>
	<?php do_action('gem_after_page_preloader'); ?>
<?php endif; ?>

<?php if(thegem_get_option('header_layout') == 'perspective') : ?>
	<div id="thegem-perspective" class="thegem-perspective effect-moveleft">
		<div class="thegem-perspective-menu-wrapper <?php echo ($thegem_header_params['header_menu_logo_light'] ? ' header-colors-light' : ''); ?> mobile-menu-layout-<?php echo esc_attr(thegem_get_option('mobile_menu_layout', 'default')); ?>">
			<nav id="primary-navigation" class="site-navigation primary-navigation perspective-navigation vertical right" role="navigation">
				<?php do_action('thegem_before_perspective_nav_menu'); ?>
				<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => apply_filters( 'thegem_nav_menu_class', 'nav-menu styled no-responsive' ), 'container' => false, 'walker' => new TheGem_Mega_Menu_Walker)); ?>
				<?php do_action('thegem_after_perspective_nav_menu'); ?>
			</nav>
		</div>
<?php endif; ?>

<div id="page" class="layout-<?php echo esc_attr(thegem_get_option('page_layout_style', 'fullwidth')); ?><?php echo esc_attr(thegem_get_option('header_layout') == 'vertical' ? ' vertical-header' : '') ; ?> header-style-<?php echo esc_attr(thegem_get_option('header_layout') == 'vertical' || thegem_get_option('header_layout') == 'fullwidth_hamburger' ? 'vertical' : thegem_get_option('header_style')); ?>">

	<?php if(!thegem_get_option('disable_scroll_top_button')) : ?>
		<a href="#page" class="scroll-top-button"></a>
	<?php endif; ?>

	<?php if(!$thegem_effects_params['effects_hide_header']) : ?>

		<?php if(thegem_get_option('top_area_style') && !$thegem_header_params['header_hide_top_area'] && (thegem_get_option('header_layout') == 'vertical' && thegem_get_option('header_layout') != 'fullwidth_hamburger' || thegem_get_option('top_area_disable_fixed'))) : ?>
			<?php get_template_part('top_area'); ?>
		<?php endif; ?>

		<div id="site-header-wrapper"  class="<?php  echo $thegem_header_params['header_transparent'] ? 'site-header-wrapper-transparent' : ''; ?> <?php echo thegem_get_option('sticky_header_on_mobile') ? ' sticky-header-on-mobile' : ''; ?>" >

			<?php if(thegem_get_option('header_layout') == 'fullwidth_hamburger') : ?><div class="hamburger-overlay"></div><?php endif; ?>

			<?php do_action('thegem_before_header'); ?>

			<header id="site-header" class="site-header<?php echo (thegem_get_option('disable_fixed_header') || thegem_get_option('header_layout') == 'vertical' ? '' : ' animated-header'); ?><?php echo thegem_get_option('header_on_slideshow') ? ' header-on-slideshow' : ''; ?> mobile-menu-layout-<?php echo esc_attr(thegem_get_option('mobile_menu_layout', 'default')); ?>" role="banner">
				<?php if(thegem_get_option('header_layout') == 'vertical') : ?><button class="vertical-toggle"><?php esc_html_e('Primary Menu', 'thegem'); ?><span class="menu-line-1"></span><span class="menu-line-2"></span><span class="menu-line-3"></span></button><?php endif; ?>
				<?php if(thegem_get_option('top_area_style') && !$thegem_header_params['header_hide_top_area'] && !thegem_get_option('top_area_disable_fixed') && thegem_get_option('header_layout') != 'vertical' && thegem_get_option('header_layout') != 'fullwidth_hamburger') : ?>
					<?php if($thegem_header_params['header_transparent']) : ?><div class="transparent-header-background" style="background-color: rgba(<?php echo esc_attr(implode(', ', hex_to_rgb(thegem_get_option('top_area_background_color')))); ?>, <?php echo intval($thegem_header_params['header_opacity'])/100; ?>);"><?php endif; ?>
					<?php get_template_part('top_area'); ?>
					<?php if($thegem_header_params['header_transparent']) : ?></div><?php endif; ?>
				<?php endif; ?>

				<?php if($thegem_header_params['header_transparent']) : ?><div class="transparent-header-background" style="background-color: rgba(<?php echo esc_attr(implode(', ', hex_to_rgb(thegem_get_option('top_background_color')))); ?>, <?php echo intval($thegem_header_params['header_opacity'])/100; ?>);"><?php endif; ?>
				<div class="container<?php echo (thegem_get_option('header_layout') == 'fullwidth' || thegem_get_option('header_layout') == 'fullwidth_hamburger' || thegem_get_option('header_layout') == 'overlay' || thegem_get_option('header_layout') == 'perspective' ? ' container-fullwidth' : ''); ?>">
					<div class="header-main logo-position-<?php echo esc_attr(thegem_get_option('logo_position', 'left')); ?><?php echo ($thegem_header_params['header_menu_logo_light'] ? ' header-colors-light' : ''); ?> header-layout-<?php echo esc_attr(thegem_get_option('header_layout')); ?> header-style-<?php echo esc_attr(thegem_get_option('header_layout') == 'vertical' || thegem_get_option('header_layout') == 'fullwidth_hamburger' ? 'vertical' : thegem_get_option('header_style')); ?>">
						<?php if(thegem_get_option('logo_position', 'left') != 'right') : ?>
							<div class="site-title">
								<?php thegem_print_logo($thegem_header_light); ?>
							</div>
							<?php if(has_nav_menu('primary')) : ?>
								<?php if(thegem_get_option('header_layout') != 'perspective') : ?>
									<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
										<?php do_action('thegem_before_nav_menu'); ?>
										<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => apply_filters( 'thegem_nav_menu_class', 'nav-menu styled no-responsive' ), 'container' => false, 'walker' => new TheGem_Mega_Menu_Walker)); ?>
										<?php do_action('thegem_after_nav_menu'); ?>
									</nav>
								<?php else: ?>
									<?php do_action('thegem_perspective_menu_buttons'); ?>
								<?php endif; ?>
							<?php endif; ?>
						<?php else : ?>
							<?php if(has_nav_menu('primary')) : ?>
								<?php if(thegem_get_option('header_layout') != 'perspective') : ?>
									<nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">
										<?php do_action('thegem_before_nav_menu'); ?>
										<?php wp_nav_menu(array('theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => apply_filters( 'thegem_nav_menu_class', 'nav-menu styled no-responsive' ), 'container' => false, 'walker' => new TheGem_Mega_Menu_Walker)); ?>
										<?php do_action('thegem_after_nav_menu'); ?>
									</nav>
								<?php else: ?>
									<?php do_action('thegem_perspective_menu_buttons'); ?>
								<?php endif; ?>
							<?php endif; ?>
							<div class="site-title">
								<?php thegem_print_logo($thegem_header_light); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php if($thegem_header_params['header_transparent']) : ?></div><?php endif; ?>
			</header><!-- #site-header -->
			<?php if(thegem_get_option('header_layout') == 'vertical') : ?>
				<div class="vertical-menu-item-widgets">
					<?php
						add_filter( 'get_search_form', 'thegem_serch_form_vertical_header' );
						get_search_form();
						remove_filter( 'get_search_form', 'thegem_serch_form_vertical_header' );
					?>
					<div class="menu-item-socials socials-colored"><?php thegem_print_socials('rounded'); ?></div></div>
			<?php endif; ?>
		</div><!-- #site-header-wrapper -->

	<?php endif; ?>

	<div id="main" class="site-main">
