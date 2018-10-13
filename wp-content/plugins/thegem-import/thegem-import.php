<?php
/*
Plugin Name: TheGem Import
Plugin URI: http://codex-themes.com/thegem/
Author: Codex Themes
Version: 3.6.0
Author URI: http://codex-themes.com/thegem/
*/


include_once ('inc/parse_content.php');
include_once ('inc/import-widgets.php');
include_once ('inc/easy-mailchimp-import.php');

function thegem_import_get_purchase() {
	if(!defined('ENVATO_HOSTED_SITE')) {
		$theme_options = get_option('thegem_theme_options');
		if($theme_options && isset($theme_options['purchase_code'])) {
			return $theme_options['purchase_code'];
		}
	} else {
		return 'envato_hosted:'.(defined('SUBSCRIPTION_CODE') ? SUBSCRIPTION_CODE : '');
	}
	return false;
}

if(!function_exists('thegem_is_plugin_active')) {
	function thegem_is_plugin_active($plugin) {
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		return is_plugin_active($plugin);
	}
}

add_action('admin_menu', 'thegem_import_submenu_page');
function thegem_import_submenu_page() {
	add_menu_page( 'TheGem Import', 'TheGem Import', 'manage_options', 'thegem-import-submenu-page', 'thegem_import_page', '', 81 );
}

function thegem_import_page() {
	$packs = array(
		'agencies' => array(
			'title' => 'Agencies',
			'pics' => array(1, 2, 3, 4, 5, 6, 7)
		),
		'apps' => array(
			'title' => 'Apps',
			'pics' => array(1, 2)
		),
		'beauty' => array(
			'title' => 'Beauty',
			'pics' => array(1, 2)
		),
		'blog' => array(
			'title' => 'Blogs',
			'pics' => array(1, 2, 3, 4)
		),
		'business' => array(
			'title' => 'Business',
			'pics' => array(1, 2, 3, 4, 5, 6)
		),
		'charity' => array(
			'title' => 'Charity, Non-Profit',
			'pics' => array(1, 2)
		),
		'church' => array(
			'title' => 'Church',
			'pics' => array(1, 2)
		),
		'coming-soon' => array(
			'title' => 'Coming Soon',
			'pics' => array(1, 2, 3, 4, 5, 6)
		),
		'architecture' => array(
			'title' => 'Construction, Architecture, Real Estate',
			'pics' => array(1,2,3,4)
		),
		'creative' => array(
			'title' => 'Creative',
			'pics' => array(1, 2, 3)
		),
		'events' => array(
			'title' => 'Events',
			'pics' => array(1, 2)
		),
		'gym' => array(
			'title' => 'Gym',
			'pics' => array(1, 2)
		),
		'hotels' => array(
			'title' => 'Hotels',
			'pics' => array(1, 2)
		),
		'ico' => array(
			'title' => 'ICO & Crypto',
			'multi' => array(
				'coin' => array(
					'pics' => array(1),
					'constant' => 'THEGEM_COIN_CHILD',
					'warning' => '<div class="warning">Import of this demo pages requires the installation of TheGem Coin child theme. You can find the TheGem_Coin_child.zip file with this child theme in Child_Theme folder of the TheGem.zip package you have downloaded.</div><div><a class="button-primary" href="'.admin_url('theme-install.php').'">Install now</a></div>'
				),
				'cryptocoin' => array(
					'pics' => array(1),
					'constant' => 'THEGEM_CRYPTOCOIN_CHILD',
					'warning' => '<div class="warning">Import of this demo pages requires the installation of TheGem CryptoCoin child theme. You can find the TheGem_CryptoCoin_child.zip file with this child theme in Child_Theme folder of the TheGem.zip package you have downloaded.</div><div><a class="button-primary" href="'.admin_url('theme-install.php').'">Install now</a></div>'
				),
			)
			
		),
		'landings' => array(
			'title' => 'Landing Pages',
			'pics' => array(1, 2, 3, 4, 5)
		),
		'lawyer' => array(
			'title' => 'Lawyers',
			'pics' => array(1, 2)
		),
		'logistics' => array(
			'title' => 'Logistics & Transportation',
			'pics' => array(1, 2)
		),
		'medical' => array(
			'title' => 'Medical',
			'pics' => array(1, 2)
		),
		'photography' => array(
			'title' => 'Photography',
			'pics' => array(1, 2)
		),
		'portfolios' => array(
			'title' => 'Portfolios',
			'pics' => array(1, 2, 3, 4)
		),
		'quickfinders' => array(
			'title' => 'Quickfinders',
			'pics' => array(1, 2, 3)
		),
		'restaurant' => array(
			'title' => 'Restaurant',
			'pics' => array(1, 2)
		),
		'shopdemos' => array(
			'title' => 'Shop (Fashion)',
			'message' => 'Please be sure you have already installed <a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=woocommerce').'" target="_blank">WooCommerce plugin</a> before importing this shop demo concepts.',
			'pics' => array(1, 2, 3, 4, 5, 6, 7, 8)
		),
		'shop_grids' => array(
			'title' => 'Shops (Creative Grids)',
			'message' => 'Please be sure you have already installed <a href="'.admin_url('plugin-install.php?tab=plugin-information&plugin=woocommerce').'" target="_blank">WooCommerce plugin</a> before importing this shop demo concepts.',
			'multi' => array(
				'creative-shop' => array(
					'pics' => array(1)
				),
				'shop-metro' => array(
					'pics' => array(1)
				),
				'nature-shop' => array(
					'pics' => array(1)
				),
				'shop-landing' => array(
					'pics' => array(1)
				),
				'shop-masonry' => array(
					'pics' => array(1)
				),
				'shop-justified' => array(
					'pics' => array(1)
				),
			)
		),
	);
?>
<div class="wrap">
	<div id="icon-tools" class="icon32"></div>
	<h2>TheGem Import</h2>
	<?php if(thegem_is_plugin_active('wordpress-importer/wordpress-importer.php')) : ?>
		<p><?php printf(__('It seems that Wordpress Import Plugin is active. Please deactivate Wordpress Import Plugin on <a href="%s">plugins page</a> to proceed with import of TheGem\'s main demo content.'), admin_url('plugins.php')); ?></p>
	<?php elseif(get_template() != 'thegem') : ?>
		<p><?php _e('Your current activated theme in not TheGem main parent theme. Please note, that this import works only with TheGem main parent theme. Please activate TheGem main parent theme before proceeding with import.'); ?></p>
	<?php elseif(!thegem_is_plugin_active('thegem-elements/thegem-elements.php')) : ?>
		<p><?php _e('Plugin "TheGem Theme Elements" is not active.'); ?></p>
	<?php elseif(!thegem_import_get_purchase()) : ?>
		<?php if(!defined('ENVATO_HOSTED_SITE')) : ?>
			<p><?php printf(__('Please enter purchase code in <a href="%s">Theme options</a>'), admin_url('themes.php?page=options-framework#activation')); ?></p>
		<?php endif; ?>
	<?php else : ?>
		<div class="thegem-import-prevent-message"><?php printf(__('The import of demo media works best on a new installation of WordPress. If you have already an existing WordPress installation, we recommend you to use "<a href="%s" target="_blank">Reset WP</a>" plugin to reset your media database and content.'), esc_url('https://wordpress.org/plugins/reset-wp/')); ?></div>
		<div class="thegem-import-output ui-no-theme">
			<div class="import-variants">

				<div id="pack-import" class="import-variant">
					<h3><?php _e('Import of selected demo concepts'); ?></h3>
					<div class="inside"><div>
							<p><?php _e('Lightweighted import option. Here you can select one purpose topic for demo import. It is useful if you are interested only in one special puprose (for example only business homepages) and don\'t need any other demos. This import will install only homepages of the selected topic as well as demo pages from "Pages" category of our demo website (About Us, Services etc.) and demo pages from "Elements" category (to give you all examples of how to use different shortcodes and elements). Please note: this import works best on a new intall of WordPress.'); ?></p>
							<div class="import-tabs">
								<ul class="clearfix">
									<?php foreach($packs as $pack => $content) : ?>
										<li><a href="#import-tab-<?php echo $pack; ?>"><?php echo $content['title']; ?></a></li>
									<?php endforeach; ?>
								</ul>
								<div class="import-tabs-content">
									<?php foreach($packs as $pack => $content) : ?>
										<div class="import-tab" id="import-tab-<?php echo $pack; ?>">
											<?php if(!isset($content['multi'])) : ?>
											<?php if(isset($content['message'])) : ?>
												<div class="import-pack-message"><?php echo $content['message']; ?></div>
											<?php endif; ?>
												<div class="import-pack-pics">
													<?php foreach($content['pics'] as $pic) : ?>
														<div class="import-pack-pic"><img src="<?php echo plugins_url( '/images/previews/'.$pack.'/'.$pic.'.jpg' , __FILE__ ) ?>" alt="#" /></div>
													<?php endforeach; ?>
												</div>
												<button class="import-button button-primary" data-import-part="full" data-import-pack="<?php echo $pack; ?>"><?php _e('Import'); ?></button>
											<?php else : ?>
											<?php if(isset($content['message'])) : ?>
												<div class="import-pack-message"><?php echo $content['message']; ?></div>
											<?php endif; ?>
												<div class="import-pack-pics multi-pack">
													<?php foreach($content['multi'] as $mpack => $pic) : ?>
														<div class="import-pack-pic">
															<img src="<?php echo plugins_url( '/images/previews/'.$mpack.'/'.$pic['pics'][0].'.jpg' , __FILE__ ) ?>" alt="#" /><br />
															<?php if(isset($pic['constant']) && !defined($pic['constant'])) : ?>
																<?php echo $pic['warning'] ?>
															<?php else : ?>
																<button class="import-button button-primary" data-import-part="full" data-import-pack="<?php echo $mpack; ?>"><?php _e('Import'); ?></button>
															<?php endif; ?>
														</div>
													<?php endforeach; ?>
												</div>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
					</div></div>
				</div>

				<div id="singles-import" class="import-variant">
					<h3><?php _e('Import of single pages, posts etc.'); ?></h3>
					<div class="inside">
						<div>
														<?php
                                                        $file = plugin_dir_path( __FILE__ ) . '/demo-files/content_singles.json';
                                                        if($file_content = file_get_contents($file)) {
																$singles_content = json_decode($file_content, true);

																$homepage_array = sort_array_for_single_item($singles_content['homepages']);
																$page_array = sort_array_for_single_item($singles_content['pages']);
																$post_array = sort_array_for_single_item($singles_content['posts']);
																$portfolio_array = sort_array_for_single_item($singles_content['portfolios']);
																$product_array = sort_array_for_single_item($singles_content['products']);

																$is_woocommerce = class_exists( 'WooCommerce' );

																function get_single_item_elem($array, $name) {
																		echo '<ul>';
																		foreach ($array as $item) {
																				if(!empty($item['children'])) {
																						echo '<li><div class="import-single-item-parent">'.$item['parent'].'</div>';
																						get_single_item_elem($item['children'], $name);
																						echo '</li>';
																				} else {
																						echo '<li><div class="import-single-item-elem">';
																						echo '<input name="'.$name.'[]" value="'.implode(',', $item['ids']).'" data-id="'.$item['id'].'" data-src="'.plugins_url('/images/thumbs/'.$name.'/'.$item['id'].'.jpg' , __FILE__ ).'" type="checkbox">';
																						$title = $name=='homepage' ? str_replace('Homepage:','',$item['title']) : $item['title'];
																						echo '<div class="import-single-item-elem-title">'.trim($title).'</div>';
																						echo '</div></li>';
																				}
																		}
																		echo '</ul>';
																}

																?>

																<form class="single-import-form import-singles">
																		<div class="import-singles-box">
																				<div class="import-single-col">
																						<div class="import-single-col-title">Homepages</div>
																						<div class="import-single-col-body homepages">
																								<?php get_single_item_elem($homepage_array, 'homepage'); ?>
																						</div>
																				</div>
																				<div class="import-single-col">
																						<div class="import-single-col-title">Pages</div>
																						<div class="import-single-col-body pages">
																								<?php get_single_item_elem($page_array, 'page'); ?>
																						</div>
																				</div>
																				<div class="import-single-col">
																						<div class="import-single-col-title">Posts</div>
																						<div class="import-single-col-body posts">
																								<?php get_single_item_elem($post_array, 'post'); ?>
																						</div>
																				</div>
																				<div class="import-single-col">
																						<div class="import-single-col-title">Portfolio</div>
																						<div class="import-single-col-body portfolios">
																								<?php get_single_item_elem($portfolio_array, 'portfolio'); ?>
																						</div>
																				</div>
																				<?php if($is_woocommerce) { ?>
																						<div class="import-single-col">
																								<div class="import-single-col-title">Products</div>
																								<div class="import-single-col-body products">
																										<?php get_single_item_elem($product_array, 'product'); ?>
																								</div>
																						</div>
																				<?php } ?>
																		</div>

																		<div class="import-singles-preview-box"></div>

																		<div class="import-singles-btn-box">
																				<input class="import-singles-button button-primary" type="submit" value="Import" />
																		</div>

																</form>

														<?php } ?>
						</div>
					</div>
				</div>

								<div id="full-import" class="import-variant">
										<h3><?php _e('Full demo import'); ?></h3>
										<div class="inside">
												<div>
														<p><?php _e('With this option you can import all demo content with one click, including import of media content (selected and optimized images & videos used on our demo website). Please note: full import & generating of image thumbnails may take from 30 min to 1 hour, depending on your server/hosting configuration.'); ?></p>
														<button class="import-button button-primary" data-import-part="full" data-import-pack="main"><?php _e('Start full demo import'); ?></button>
												</div>
										</div>
								</div>

								<div id="partical-import" class="import-variant">
										<h3><?php _e('Partial demo import'); ?></h3>
										<div class="inside">
												<div>
														<p><?php _e('With this option you can import all posts/pages and media content separately. For example, if you don\'t want to import our demo images & videos, you can click on "Import content without media". This import runs very fast and is done in minutes. In case you wish to import only media demo content (or add our demo images & videos after you\'ve made "Import content without media") you can click on "Import only media demo content".'); ?></p>
														<button class="import-button button-primary" data-import-part="posts" data-import-pack="main"><?php _e('Import content without media'); ?></button>
														<button class="import-button button-primary" data-import-part="media" data-import-pack="main"><?php _e('Import only media demo content'); ?></button>
												</div>
										</div>
								</div>

								<div id="remove-demo-content" class="import-variant">
										<h3><?php _e('Remove demo content'); ?></h3>
										<div class="inside">
												<div>
														<p><?php _e('With this option you can remove all demo content, which you have imported and which you have not modified.'); ?></p>
														<p><?php _e('IMPORTANT: In case you have edited (and saved) some posts, pages etc., this content will not be removed. This concerns media content as well. If you wish to keep some demo media on your website, you need to remove the word „(Demo)“ from the media description of the corresponding media file (image, video etc), otherwise this file will be removed as well.'); ?></p>
														<button id="btn-remove-demo-content" type="button" class="button-primary"><?php _e('Remove demo content'); ?></button>
												</div>
										</div>
								</div>

								<div id="widgets-import" class="import-variant">
										<h3><?php _e('Import demo widgets'); ?></h3>
										<div class="inside">
												<div>
														<p><?php _e('Here you can import all demo widgets we have used on our demo website. '); ?></p>
														<button id="btn-import-widgets" type="button" class="button-primary"><?php _e('Import demo widgets'); ?></button>
												</div>
										</div>
								</div>

								<?php $is_easy_mailChimp = class_exists('Yikes_Inc_Easy_MailChimp_Import_Class');
										if($is_easy_mailChimp) { ?>
										<div id="mailchimp-forms-import" class="import-variant">
												<h3><?php _e('Import newsletter forms'); ?></h3>
												<div class="inside">
														<div>
																<p><?php _e('Here you can import all newsletter subscription forms used on our demo website.'); ?></p>
																<button id="btn-import-mailchimp-forms" type="button" class="button-primary"><?php _e('Import newsletter forms'); ?></button>
														</div>
												</div>
										</div>
								<?php } ?>

			</div>
		</div>
	<?php endif; ?>
</div>
<?php
}

function thegem_import_enqueue($hook) {
	if($hook == 'toplevel_page_thegem-import-submenu-page') {
		wp_enqueue_script('thegem-import-scripts', plugins_url( '/js/ti-scripts.js' , __FILE__ ), array('jquery', 'jquery-ui-accordion', 'jquery-ui-tabs'), false, true);
		wp_localize_script('thegem-import-scripts', 'thegem_import_data', array(
			'ajax_url' => admin_url('admin-ajax.php')
		));
		wp_enqueue_style('thegem-import-css', plugins_url( '/css/ti-styles.css' , __FILE__ ));
	}
}
add_action('admin_enqueue_scripts', 'thegem_import_enqueue');

add_action('wp_ajax_thegem_import_files_list', 'thegem_import_files_list');
function thegem_import_files_list () {
	$response_p = wp_remote_get(add_query_arg(array('code' => thegem_import_get_purchase(), 'site_url' => get_site_url()), 'http://democontent.codex-themes.com/av_validate_code'.(defined('ENVATO_HOSTED_SITE') ? '_envato' : '').'.php'), array('timeout' => 20));
	if(!is_wp_error($response_p)) {
		$rp_data = json_decode($response_p['body'], true);
		if(is_array($rp_data) && isset($rp_data['result']) && $rp_data['result'] && isset($rp_data['item_id']) && $rp_data['item_id'] === '16061685') {
			if(isset($_REQUEST['import_pack']) && isset($_REQUEST['import_part'])) {
				$response = wp_remote_get(add_query_arg(array('import_pack' => $_REQUEST['import_pack'], 'import_part' => $_REQUEST['import_part']), 'http://democontent.codex-themes.com/democontent-packs-new/index.php'), array('timeout' => 20));
				if(!is_wp_error($response)) {
					echo $response['body'];
					if($_REQUEST['import_part']=='posts') {
						update_option('import_attachment_json_data', thegem_get_attachment_json_data());
					}
				} else {
					echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Some troubles with connecting to demo-content server.'));
				}
			} else {
				echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Sending data error.'));
			}
		} else {
			if(!defined('ENVATO_HOSTED_SITE')) {
					echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Purchase code verification failed. <a href="'.esc_url(admin_url('themes.php?page=options-framework#activation')).'">Activate TheGem</a>'));
			} else {
				echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Verification failed.'));
			}
		}
	} else {
		echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Some troubles with connecting to demo-content server.'));
	}
	die(-1);
}

add_action('wp_ajax_thegem_import_sliders_list', 'thegem_import_sliders_list');
function thegem_import_sliders_list () {
	$sliders = get_option('thegem_revsliders_for_import');
	$sliders = !empty($sliders) && is_array($sliders) ? array_values(array_unique($sliders)) : array();
	echo json_encode(array('status' => 1, 'status_text' => 'Sliders import is running. Please don\'t close this window until import is finished.', 'message' => 'Importing sliders', 'files_list' => $sliders));
	die(-1);
}

add_action('wp_ajax_thegem_import_file', 'thegem_import_file');
function thegem_import_file () {
	$filedir = '/packs/thegem/';
	if(isset($_REQUEST['import_pack']) && ($_REQUEST['import_pack'] !== 'main')) {
		$filedir = '/packs/'.$_REQUEST['import_pack'].'/';
	}

	if(!empty($_REQUEST['filename'])) {
		ob_start();
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		$tmp = download_url('http://democontent.codex-themes.com/democontent-packs-new' .$filedir.$_REQUEST['filename']);
		if( is_wp_error( $tmp ) ) {
			print_r($tmp->get_error_messages());
		} else {
			if (! defined('WP_LOAD_IMPORTERS')) define('WP_LOAD_IMPORTERS', true);
			require_once(plugin_dir_path( __FILE__ ) . '/inc/wordpress-importer.php');
			$wp_import = new WP_Import();
			$wp_import->fetch_attachments = true;
			$result = $wp_import->import($tmp);
			if($result === 10 || $result === 20) {
				@unlink( $tmp );
				$message = ob_get_clean();
				echo json_encode(array('status' => $result, 'status_text' => 'Timeout detected.', 'message' => 'Timeout detected. Ajax restart.', 'texts' => $message));
				die(-1);
			}
		}
		@unlink( $tmp );
		$messages = ob_get_clean();
		echo json_encode(array('status' => 1, 'message' => 'Done. <!-- '.$messages.' -->'));
	}
	die(-1);
}

add_action('wp_ajax_thegem_import_slider', 'thegem_import_slider');
function thegem_import_slider () {
	if(!empty($_REQUEST['alias'])) {
		ob_start();
		$result = thegem_import_revslider($_REQUEST['alias']);
		echo $result['message'];
		$messages = ob_get_clean();
		echo json_encode(array('status' => 1, 'message' => 'Done. <!-- '.$messages.' -->'));
	}
	die(-1);
}

function thegem_import_replace_array($dir = 1) {
	$packs = array(
		'agencies',
		'apps',
		'beauty',
		'blog',
		'business',
		'charity',
		'church',
		'coming-soon',
		'architecture',
		'creative',
		'events',
		'gym',
		'hotels',
		'landings',
		'lawyer',
		'logistics',
		'medical',
		'photography',
		'portfolios',
		'quickfinders',
		'restaurant',
		'shopdemos',
		'creative-shop',
		'shop-metro',
		'nature-shop',
		'shop-landing',
		'shop-masonry',
		'shop-justified',
		'coin',
		'cryptocoin'
	);
	if($dir === 1) {
		$replace_array = array('http://democontent.codex-themes.com/thegem/wp-content/uploads');
	} else {
		$replace_array = array('http://democontent.codex-themes.com/thegem/wp-content/themes/TheGem');
	}
	foreach($packs as $pack) {
		if($dir === 1) {
			$replace_array[] = 'http://democontent.codex-themes.com/thegem-'.$pack.'/wp-content/uploads';
		} else {
			$replace_array[] = 'http://democontent.codex-themes.com/thegem-'.$pack.'/wp-content/themes/TheGem';
		}
	}
	return $replace_array;
}

add_filter('wp_import_post_data_raw', 'thegem_import_wp_import_post_data_raw');
function thegem_import_wp_import_post_data_raw($post) {
	$upload_dir = wp_upload_dir();
	$post['post_content'] = str_replace(thegem_import_replace_array(), $upload_dir['baseurl'], $post['post_content']);
	$post['post_content'] = str_replace(thegem_import_replace_array(2), get_template_directory_uri(), $post['post_content']);

	if($post['post_type'] != 'nav_menu_item') {
				$post['post_title'] = $post['post_title'].' (Demo)';
		}

	if(!empty($post['post_content'])) {
		$attachments_data = get_option('import_attachment_json_data');
		if(!empty($attachments_data)) {
			$post['post_content'] = thegem_replace_attachments_content($post['post_content'], $attachments_data);
		}
	}

	return $post;
}

add_filter('wp_import_post_terms', 'thegem_wp_import_terms');
function thegem_wp_import_terms($terms) {
		if(!empty($terms)) {
				foreach ($terms as $key => $value) {
						$terms[$key]['name']=$terms[$key]['name'].' (Demo)';
				}
		}
		return $terms;
}

add_action( 'wp_import_insert_post', 'thegem_wp_import_insert_post', 10, 4 );
function thegem_wp_import_insert_post($post_id, $original_post_ID, $postdata, $post) {

		if(get_option('thegem_is_import_singles')) {
				$thegem_import_singles_ids = get_option('thegem_import_singles_ids');
				if(!empty($thegem_import_singles_ids)) {
						$thegem_import_singles_ids[] = $post_id;
						update_option('thegem_import_singles_ids', $thegem_import_singles_ids);
				} else {
						update_option('thegem_import_singles_ids', array($post_id));
				}

		}
}


add_filter('import_post_meta', 'thegem_import_post_meta', 11, 3);
function thegem_import_post_meta($post_id, $key, $value) {
	$upload_dir = wp_upload_dir();
	if(is_array($value)) {
		foreach($value as $k => $v) {
			if(is_array($v)) {
				foreach($v as $a => $b) {
					$value[$k][$a] = str_replace(thegem_import_replace_array(), $upload_dir['baseurl'], $value[$k][$a]);
					$value[$k][$a] = str_replace(thegem_import_replace_array(2), get_template_directory_uri(), $value[$k][$a]);
				}
			} else {
				$value[$k] = str_replace(thegem_import_replace_array(), $upload_dir['baseurl'], $value[$k]);
				$value[$k] = str_replace(thegem_import_replace_array(2), get_template_directory_uri(), $value[$k]);
			}
		}
	} else {
		$value = str_replace(thegem_import_replace_array(), $upload_dir['baseurl'], $value);
		$value = str_replace(thegem_import_replace_array(2), get_template_directory_uri(), $value);
	}
	if($key == 'thegem_page_data' && is_array($value)) {
		if(!empty($value['slideshow_type']) && $value['slideshow_type'] == 'revslider' && !empty($value['slideshow_revslider'])) {
			$sliders = get_option('thegem_revsliders_for_import');
			$sliders = !empty($sliders) && is_array($sliders) ? array_values(array_unique($sliders)) : array();
			$sliders[] = $value['slideshow_revslider'];
			update_option('thegem_revsliders_for_import', $sliders);
		}
	}
	update_post_meta($post_id, $key, $value);
}

function thegem_import_revslider($alias) {
	$slider_link = thegem_import_slider_link($alias);
	if(!empty($slider_link)) {
		if (class_exists( 'RevSlider' ) ) {
			$slider = new RevSlider();
			if(!$slider->isAliasExists($alias)) {
				require_once(ABSPATH . 'wp-admin/includes/file.php');
				$tmp = download_url($slider_link);
				if( is_wp_error( $tmp ) ) {
					return array('status' => false, 'message' => 'Can\'t download slider archive: '.$tmp->get_error_messages());
				} else {
					$result = $slider->importSliderFromPost(true,true,$tmp);
					return array('status' => true, 'message' => 'Import result: '.print_r($result, 1));
				}
			} else {
				return array('status' => false, 'message' => 'Slider with alias "'.$alias.'" is exists.');
			}
		} else {
			return array('status' => false, 'message' => 'RevSlider plugin not installed.');
		}
	} else {
		return array('status' => false, 'message' => 'Alias "'.$alias.'" not found.');
	}
}

function thegem_import_slider_link($slider) {
	$sliders = array(
		'agency-rave-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Agency%20Bright.zip',
		'ahegem-app' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/App%20Landing%20Content%20Slider.zip',
		'thegem-app' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/App%20Landing.zip',
		'app-sparta-content-slider' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/App%20Onepager%20Content%20Slider.zip',
		'App Sparta Animate' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/App%20Onepager.zip',
		'business-02' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Business%2002.zip',
		'business-03' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Business%2003.zip',
		'business-05-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Business%2005.zip',
		'business-06' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Business-06.zip',
		'event_01' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Business%20&%20IT%20Event%20(One%20Page).zip',
		'landing-page-3' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Classic%20Landing.zip',
		'Creative Agency' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Creative%20Agency%20(One-Page).zip',
		'splash-creative-light-01-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Creative%20Bright.zip',
		'Splash Creative Dark Animate' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Creative%20Dark.zip',
		'splash-creative-light-02-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Creative%20Light.zip',
		'agency-sparta-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Digital%20Agency.zip',
		'fashion-photography-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Fashion%20Photography.zip',
		'shop-splash-page-1-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Fashion%20Store.zip',
		'Products Grid' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Grid-Based%20Shop.zip',
		'fitness-slider-1-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Gym%20&%20Fitness%2001%20-%201.zip',
		'fitness-slider-2-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Gym%20&%20Fitness%2001%20-%202.zip',
		'fitness-slider-3-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Gym%20&%20Fitness%2002.zip',
		'medical-2-anumated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Health%20and%20Medical.zip',
		'hotel-2-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Hotel%2002.zip',
		'interior-sparta-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Interior%20&%20Architecture.zip',
		'lawyer-01-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Lawyer%2001.zip',
		'lookbook-slider-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Lookbook%20slider%20.zip',
		'Masonry Shop' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Masonry%20Style%20Shop.zip',
		'metro-shop-single-slide' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Metro%20Style%20Shop.zip',
		'shop-splash-page-3-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Online%20Shop.zip',
		'gem-photo-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Photography%20Studio.zip',
		'portfolio-minimalistic1-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Portfolio%20Minimalistic.zip',
		'restaurant-2-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Restaurant%201.zip',
		'restaurant-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Restaurant%202.zip',
		'shop-landing-content-slider' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Shop%20Landing-Content%20Slider.zip',
		'shop-landing' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Shop%20Landing.zip',
		'shop-splash-page-2-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Shop%20&%20Lookbook.zip',
		'single-shop-slide' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Single%20Shop%20Slide.zip',
		'TheGem Agency (One-Page)' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/TheGem%20Agency%20(One-Page).zip',
		'Thegem Church Onepage' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Thegem%20Church%20Onepage.zip',
		'church-02' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/The%20Gem%20Church.zip',
		'logistics-02-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/TheGem%20Logistic.zip',
		'logistics-01' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Transport%20&%20Logistics.zip',
		'splash-shop-page-04-animate' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Trendy%20Shop.zip',
		'vertical slider' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Vertical%20Slider.zip',
		'Construction-02' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/Construction-02.zip',
		'handyman-slider' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/handyman-slider.zip',
		'craftsman-slider' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/craftsman-slider.zip',
		'handyman-onepager-slider' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/handyman-onepager-slider.zip',
		'charity-slider' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/charity-slider.zip',
		'charity-one-pager-slider' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/homepages/charity-one-pager-slider.zip',

		'about-us-page-1-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/About%20Us%20Page%201.zip',
		'about-us-page-2-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/About%20Us%20Page%20%202.zip',
		'Blog masonry 3x light' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Blog%20masonry%203x.zip',
		'contact-us-01-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Contact%20Us%2001.zip',
		'features-fullscreen-showcase-page' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Features%20Fullscreen%20Showcase%20Page.zip',
		'features-one-pager-options-page' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Features%20One%20Pager%20Options%20Page.zip',
		'features-visual-composer-page' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Features%20Visual%20Composer%20Page.zip',
		'galleries-page-01' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Galleries%2001.zip',
		'galleries-page-02' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Galleries%2002.zip',
		'galleries-page-03' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Galleries%2003.zip',
		'SIMPLE PROJECT' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Portfolio%20-%20Architecture%20&%20Interior%202.zip',
		'shop-general-page-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/Shop_Main_Page.zip',

		'gemcoin-hero-slider-animated' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/gemcoin-hero-slider-animated.zip',
		'gem-cryptocoin' => 'http://democontent.codex-themes.com/democontent-packs-new/revolution_sliders/other_pages/gem-cryptocoin.zip',
	);

	return !empty($sliders[$slider]) ? $sliders[$slider] : false;
}

add_action('wp_ajax_thegem_delete_attachment_json_data', 'thegem_delete_attachment_json_data');
function thegem_delete_attachment_json_data() {
	delete_option('import_attachment_json_data');
	delete_option('thegem_revsliders_for_import');
}

add_action('wp_ajax_thegem_import_download_content', 'thegem_import_download_content');
function thegem_import_download_content() {
		$response_p = wp_remote_get(add_query_arg(array('code' => thegem_import_get_purchase(), 'site_url' => get_site_url()), 'http://democontent.codex-themes.com/av_validate_code'.(defined('ENVATO_HOSTED_SITE') ? '_envato' : '').'.php'), array('timeout' => 20));
		if(!is_wp_error($response_p)) {
				$rp_data = json_decode($response_p['body'], true);
				if(is_array($rp_data) && isset($rp_data['result']) && $rp_data['result'] && isset($rp_data['item_id']) && $rp_data['item_id'] === '16061685') {
						if(isset($_REQUEST['ids']) && !empty($_REQUEST['ids'])) {
								$file_content = 'http://democontent.codex-themes.com/democontent-packs-new/packs/thegem-singles/content.xml';
								ob_start();
								require_once(ABSPATH . 'wp-admin/includes/file.php');
								$tmp = download_url($file_content);
								if( is_wp_error( $tmp ) ) {
										print_r($tmp->get_error_messages());
								} else {
									$wp_upload_dir = wp_upload_dir();
									if(!wp_mkdir_p($wp_upload_dir['basedir'] . '/thegem-import')) {
										echo 'Error: can\'t create demo-content directory';
									} else {
										$content_file_path = $wp_upload_dir['basedir'] . '/thegem-import/content.xml';
										if( file_exists($content_file_path) ) {
											@unlink( $content_file_path );
										}
										$move_new_file = @copy($tmp, $content_file_path);
										if($move_new_file === false) {
											echo 'Error: demo-content file not uploaded';
										}
									}
								}
								@unlink( $tmp );
								$message = ob_get_clean();

								echo json_encode(array('status' => 1, 'message'=>$message));
						} else {
								echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Error on import: empty ids value.'));
						}
				} else {
						if(!defined('ENVATO_HOSTED_SITE')) {
								echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Purchase code verification failed. <a href="'.esc_url(admin_url('themes.php?page=options-framework#activation')).'">Activate TheGem</a>'));
						} else {
								echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Verification failed.'));
						}
				}
		} else {
				echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Some troubles with connecting to demo-content server.'));
		}
		die(-1);
}

add_action('wp_ajax_thegem_singles_progressbar_status', 'thegem_singles_progressbar_status');
function thegem_singles_progressbar_status() {
	$status = get_option('thegem_import_singles_status');
	if(is_array($status) && isset($status['num']) && isset($status['total'])) {
		echo json_encode($status);
	} else {
		echo json_encode(array('num' => 0, 'total'=>100));
	}
	die(-1);
}

add_action('wp_ajax_thegem_import_singles', 'thegem_import_singles');
function thegem_import_singles() {
		$response_p = wp_remote_get(add_query_arg(array('code' => thegem_import_get_purchase(), 'site_url' => get_site_url()), 'http://democontent.codex-themes.com/av_validate_code'.(defined('ENVATO_HOSTED_SITE') ? '_envato' : '').'.php'), array('timeout' => 20));
		if(!is_wp_error($response_p)) {
				$rp_data = json_decode($response_p['body'], true);
				if(is_array($rp_data) && isset($rp_data['result']) && $rp_data['result'] && isset($rp_data['item_id']) && $rp_data['item_id'] === '16061685') {
						if(isset($_REQUEST['ids']) && !empty($_REQUEST['ids'])) {
								$ids = thegem_parse_ids_request($_REQUEST['ids']);
								$wp_upload_dir = wp_upload_dir();
								$file_content = $wp_upload_dir['basedir'] . '/thegem-import/content.xml';
								ob_start();
								require_once(ABSPATH . 'wp-admin/includes/file.php');
								if( !file_exists($file_content) ) {
									echo 'Error: demo-content file doesn exists.';
								} else {
										if (! defined('WP_LOAD_IMPORTERS')) define('WP_LOAD_IMPORTERS', true);
										require_once(plugin_dir_path( __FILE__ ) . '/inc/wordpress-importer.php');
										$wp_import_single = new WP_Import();
										$wp_import_single->fetch_attachments = true;
										update_option('thegem_is_import_singles', true);
										update_option('thegem_import_singles_status', array('num' => 0, 'total' => count($ids)));
										$result = $wp_import_single->import($file_content, $ids);
										if($result === 10 || $result === 20) {
											$message = ob_get_clean();
											echo json_encode(array('status' => $result, 'status_text' => 'Timeout detected.', 'message' => 'Timeout detected. Ajax restart.'));
											die(-1);
										}
								}
								@unlink( $file_content );
								$message = ob_get_clean();

								$import_ids = get_option('thegem_import_singles_ids');
								delete_option('thegem_is_import_singles');
								delete_option('thegem_import_singles_ids');
								delete_option('thegem_import_singles_status');

								echo json_encode(array('status' => 1, 'data'=>$import_ids, 'message'=>$message, 'slider_status' => $slider_status));
						} else {
								echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Error on import: empty ids value.'));
						}
				} else {
						if(!defined('ENVATO_HOSTED_SITE')) {
								echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Purchase code verification failed. <a href="'.esc_url(admin_url('themes.php?page=options-framework#activation')).'">Activate TheGem</a>'));
						} else {
								echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Verification failed.'));
						}
				}
		} else {
				echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'Some troubles with connecting to demo-content server.'));
		}
		die(-1);
}


add_action('wp_ajax_thegem_import_singles_update_content', 'thegem_import_singles_update_content');
function thegem_import_singles_update_content() {
	if(isset($_REQUEST['ids']) && !empty($_REQUEST['ids'])) {
		$ids = $_REQUEST['ids'];
		$attachments_data = thegem_get_attachment_json_data('singles');
		$product_categories_data = thegem_get_categories_product_json_data();

		$query = new WP_Query( array('post_status' => 'any', 'post_type' => 'any', 'post__in' => $ids, 'posts_per_page'=>-1) );
		while ( $query->have_posts() ) {
			$query->the_post();
			$p = get_post();
						if(get_post_type() != 'attachment' && !empty($p->post_content)) {
								$p->post_content = thegem_replace_attachments_content($p->post_content, $attachments_data);
								$p->post_content = thegem_replace_product_categories_content($p->post_content, $product_categories_data);
								wp_update_post($p);
						}
		}
		echo json_encode(array('status' => 1));
	}

	die(-1);
}

function thegem_get_attachment_json_data($type='') {
		if($type=='singles') {
				$filedir = '/packs/thegem-singles/';
		} else {
				$filedir = '/packs/thegem/';
				if(isset($_REQUEST['import_pack']) && ($_REQUEST['import_pack'] !== 'main')) {
						$filedir = '/packs/'.$_REQUEST['import_pack'].'/';
				}
		}

		$filename = 'content_attachments.json';
		$data = array();
		$tmp = download_url('http://democontent.codex-themes.com/democontent-packs-new'.$filedir.$filename);

		if( is_wp_error( $tmp ) ) {
				print_r($tmp->get_error_messages());
		} else {
				$file_content = file_get_contents($tmp);
				$file_data = json_decode($file_content, true);
				if(!empty($file_data)) {
						$data = thegem_get_ids_attachment($file_data);
				}
				unlink($tmp);
		}

		return $data;
}


function thegem_get_categories_product_json_data() {
		$data = array();
		$tmp = download_url('http://democontent.codex-themes.com/democontent-packs-new/packs/thegem-singles/content_product_categories.json');

		if( is_wp_error( $tmp ) ) {
				print_r($tmp->get_error_messages());
		} else {
				$file_content = file_get_contents($tmp);
				$file_data = json_decode($file_content, true);
				if(!empty($file_data)) {
						$data = thegem_get_product_categories_ids($file_data);
				}
				unlink($tmp);
		}

		return $data;
}

add_action('wp_ajax_thegem_remove_import_demo_content', 'thegem_remove_import_demo_content');
function thegem_remove_import_demo_content() {
		global $wpdb;
		$demo_prefix = $wpdb->esc_like('(Demo)');
		$demo_prefix = '%'.$demo_prefix.'%';

		//deleted attachments
		$sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s AND post_type = %s", $demo_prefix, 'attachment');
		$attachments = $wpdb->get_results($sql, OBJECT);
		foreach ($attachments as $key => $value) {
				wp_delete_attachment($value->ID, true);
		}

		//deleted posts
		$sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title LIKE %s", $demo_prefix);
		$posts = $wpdb->get_results($sql, OBJECT);
		foreach ($posts as $key => $value) {
				wp_delete_post($value->ID, true);
		}

		//deleted terms
		$sql = $wpdb->prepare("SELECT term_id FROM $wpdb->terms WHERE name LIKE %s", $demo_prefix);
		$terms = $wpdb->get_results($sql, OBJECT);
		foreach ($terms as $key => $value) {
				$term = get_term($value->term_id);
				wp_delete_term($term->term_id, $term->taxonomy);
		}

		die(-1);
}


add_action('wp_ajax_thegem_import_widgets', 'thegem_import_widgets');
function thegem_import_widgets() {
		$file = plugins_url('/demo-files/widgets.json', __FILE__);
		$response = file_get_contents($file);
		if($response) {
				$file_data = json_decode($response);
				$result = thegem_widgets_import_data($file_data);
				if($result['status']) {
						echo json_encode(array('status' => 1, 'status_text' => 'Import success.'));
				} else {
						echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => $result['message']));
				}
		} else {
				echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => 'There was a problem opening the file.'));
		}

		die(-1);
}


add_action('wp_ajax_thegem_import_mailchimp_forms', 'thegem_import_mailchimp_forms');
function thegem_import_mailchimp_forms() {
		$file = plugins_url('/demo-files/mailchimp-forms.csv', __FILE__);
		$result = thegem_mailchimp_import_forms($file);
		if($result['status']) {
				echo json_encode(array('status' => 1, 'status_text' => 'Import success.'));
		} else {
				echo json_encode(array('status' => 0, 'status_text' => 'Import failed.', 'message' => $result['message']));
		}
		die(-1);
}