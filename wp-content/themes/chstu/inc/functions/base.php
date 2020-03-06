<?php

// Remove mata generators
remove_action('wp_head','feed_links_extra', 3); // ссылки на дополнительные rss категорий
remove_action('wp_head','feed_links', 2); //ссылки на основной rss и комментарии
remove_action('wp_head','rsd_link');  // для сервиса Really Simple Discovery
remove_action('wp_head','wlwmanifest_link'); // для Windows Live Writer
remove_action('wp_head','wp_generator');  // убирает версию wordpress

// Remove links when show posts (prev, next, original url...)
remove_action('wp_head','start_post_rel_link',10,0);
remove_action('wp_head','index_rel_link');
remove_action('wp_head','rel_canonical');
remove_action('wp_head','adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action('wp_head','wp_shortlink_wp_head', 10, 0 );

// Disable Rest API
//add_filter('rest_enabled', '__return_false');

// Disable filters Rest API
//remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
//remove_action('wp_head', 'rest_output_link_wp_head', 10, 0);
//remove_action('template_redirect', 'rest_output_link_header', 11, 0);
//remove_action('auth_cookie_malformed', 'rest_cookie_collect_status');
//remove_action('auth_cookie_expired', 'rest_cookie_collect_status');
//remove_action('auth_cookie_bad_username', 'rest_cookie_collect_status');
//remove_action('auth_cookie_bad_hash', 'rest_cookie_collect_status');
//remove_action('auth_cookie_valid', 'rest_cookie_collect_status');
//remove_filter('rest_authentication_errors', 'rest_cookie_check_errors', 100);

// Disable events Rest API
//remove_action('init', 'rest_api_init');
//remove_action('rest_api_init', 'rest_api_default_filters', 10, 1);
//remove_action('parse_request', 'rest_api_loaded');

// Disabled embeds linked with Rest API
//remove_action('rest_api_init', 'wp_oembed_register_route');
//remove_filter('rest_pre_serve_request', '_oembed_rest_pre_serve_request', 10, 4);

// Remove emoji icons
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Disabled oembed auto discovery
// Don't filter oEmbed results.
remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

// Remove oembed doscovery links
remove_action('wp_head', 'wp_oembed_add_discovery_links');

// Remove oembed-specific js from the front-end and back-end
remove_action('wp_head', 'wp_oembed_add_host_js');

// Remove <p> in category description
remove_filter('term_description','wpautop');

// Remove dns prefetch
//function remove_dns_prefetch($hints, $relation_type){
//	if ('dns-prefetch' === $relation_type) {
//		return array_diff(wp_dependencies_unique_hosts(), $hints);
//	}
//	return $hints;
//}
//add_filter('wp_resource_hints', 'remove_dns_prefetch', 10, 2);

// Clean up output of stylesheet <link> tags
function clean_style_tag($input) {
	preg_match_all( "!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches );
	if ( empty( $matches[2] ) ) {
		return $input;
	}
	// Only display media if it is meaningful
	$media = $matches[3][0] !== '' && $matches[3][0] !== 'all' ? ' media="' . $matches[3][0] . '"' : '';
	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
}
add_filter('style_loader_tag',  'clean_style_tag');

// Clean up output of <script> tags
function clean_script_tag($input) {
	$input = str_replace( "type='text/javascript' ", '', $input );
	return str_replace( "'", '"', $input );
}
add_filter('script_loader_tag', 'clean_script_tag');

// Is environment not local (dev or prod)
function execute_for_remote($user_func){
	if ( is_env_remote() ){
		call_user_func($user_func);
	} else {
		return false;
	}
}
function is_env_remote(){
	if ( defined('ENVIRONMENT') && (ENVIRONMENT === 'dev' || ENVIRONMENT === 'prod') ){
		return true;
	} else {
		return false;
	}
}

// Environment for Dev or Prod (or any remote servers)
execute_for_remote(function (){

	// Disable theme auto update
	add_filter('auto_update_theme', '__return_false');

	// Disable plugins auto update
	add_filter('auto_update_plugin', '__return_false');

	// Disable update notice
	function disable_uptades_notice(){
		remove_action('admin_notices', 'update_nag', 3);
	}
	add_action('admin_head', 'disable_uptades_notice', 1);

	// Remove core updates
	function remove_core_updates(){
		global $wp_version;
		return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
	}
	add_filter('pre_site_transient_update_core','remove_core_updates');
	add_filter('pre_site_transient_update_plugins','remove_core_updates');
	add_filter('pre_site_transient_update_themes','remove_core_updates');

	disable_acf_init();

	action_disable_admin_pages();
});

// Disable ACF initialization and menu item admin panel
function disable_acf_init(){
	include_once(ABSPATH.'wp-admin/includes/plugin.php');
	if ( is_plugin_active('advanced-custom-fields-pro/acf.php') ) {
		add_filter('acf/settings/show_admin', '__return_false');
	}
}

// Custom login logo
function custom_login_logo(){
	echo '<style type="text/css">
	h1 a { 
		width: 320px !important;
		height: 84px !important;
		background: url('.get_bloginfo('template_directory').'/assets/admin-login-custom-logo.png) no-repeat center / contain !important; 
	}
	</style>';
}
add_action('login_head', 'custom_login_logo');

// Custom image background for admin panel
function fix_svg_thumb_display() {
	$acf_img_bg_url = get_template_directory_uri().'/assets/admin-acf-image-bg.gif';
	echo '
		<style type="text/css">
	    td.media-icon img[src$=".svg"],
	    img[src$=".svg"].attachment-post-thumbnail {
	      width: 100% !important;
	      height: auto !important;
	    }
	    .acf-image-uploader .image-wrap img{
	        background: url('.$acf_img_bg_url.') !important;
	    }
	    </style>';
}
add_action('admin_head', 'fix_svg_thumb_display');

// Localization
function get_textdomain(){
	$theme = wp_get_theme();
	return $theme->template;
}
function init_localization(){
	load_theme_textdomain(get_textdomain(), get_template_directory().'/bundle');
}
add_action('after_setup_theme', 'init_localization');
function get_localization_string($string){
	return __($string, get_textdomain());
}
function the_localization_string($string){
	echo get_localization_string($string);
}
function get_localization_context_string($string, $context){
	return _x($string, $context, get_textdomain());
}
function the_localization_context_string($string, $context){
	echo get_localization_context_string($string, $context);
}

// Disable useless elements for admin panel
//function add_admin_system_pages_styles(){
//	echo '
//		<style>
//			#titlediv,
//			#wpm-page-languages{
//				display: none !important;
//			}
//			#acf_after_title-sortables{
//				margin-top: 0 !important;
//			}
//		</style>';
//}
//function disable_useless_elements() {
//	global $my_admin_page;
//	$screen = get_current_screen();
//	if ( is_admin() ) {
//		if ($screen->id == 'page'){
//			global $post;
//			$page_id = (int)$post->ID;
//
//			if (
//				( $page_id === get_options_page_id() ) ||
//				( $page_id === get_front_page_id() ) ||
//				( $page_id === get_ueid_page_id() )
//			){
//				add_admin_system_pages_styles();
//			}
//		}
//	}
//}
//add_action('admin_head', 'disable_useless_elements');

// Disable comments
function disable_comments_menu(){
	if ((int)get_option('comment_registration') === 1){
		remove_menu_page('edit-comments.php');
	}
}
add_action('admin_menu', 'disable_comments_menu', 999);

// Disable secondary pages for admin panel
function action_disable_admin_pages(){
	function disable_console_page(){
		remove_menu_page('index.php');
	}
	add_action('admin_menu', 'disable_console_page', 999);
}

// Disable block editor
function disable_block_editor($use_block_editor) {
	return false;
}
add_filter('use_block_editor_for_post_type', 'disable_block_editor');

//if (function_exists('acf_add_page')){
//	acf_add_page();
//}
function get_options_page_id(){
	return get_translated_page_id_by_slug('options');
}

function get_front_page_id(){
	return (int)get_option('page_on_front');
}

function get_cookies_page_id(){
	return get_translated_page_id_by_slug('cookies');
}

function get_privacy_policy_page_id(){
	return get_translated_page_id_by_slug('privacy-policy');
}

/*function set_page_private_status(){
	$page_id = get_options_page_id();
	if ( get_post_status($page_id) !== 'private' ){
		wp_update_post(array(
			'ID' => $page_id,
			'post_status' => 'private'
		));
	}
}
add_action('after_setup_theme', 'set_page_private_status');*/

// Add items to admin menu
function admin_add_menus() {

	add_menu_page(
		get_localization_string('Options'),
		get_localization_string('Options'),
		'edit_pages',
		'post.php?post='.get_options_page_id().'&action=edit',
		'',
		'dashicons-admin-generic',
		2
	);

	add_menu_page(
		get_localization_string('Front page'),
		get_localization_string('Front page'),
		'edit_pages',
		'post.php?post='.get_front_page_id().'&action=edit',
		'',
		'dashicons-layout',
		2
	);

	add_menu_page(
		'Cookies',
		'Cookies',
		'edit_pages',
		'post.php?post='.get_cookies_page_id().'&action=edit',
		'',
		'dashicons-awards',
		2
	);

	add_menu_page(
		'Privacy policy',
		'Privacy policy',
		'edit_pages',
		'post.php?post='.get_privacy_policy_page_id().'&action=edit',
		'',
		'dashicons-awards',
		2
	);
}
add_action('admin_menu', 'admin_add_menus');


// Enable menu support in template
add_theme_support('menus');


function redirect_to_front_page(){
	wp_redirect(home_url());
	exit();
}
function redirect_system_pages(){
	if ( is_default_page('options') ){
		redirect_to_front_page();
	}
}
add_action('template_redirect', 'redirect_system_pages');


function redirect_pages(){

	$redirections_field = 'url_redirections';

	if ( function_exists('have_rows') && have_rows($redirections_field, get_options_page_id()) ){

		function get_changed_protocol_url($url){

			$current_protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

			$from_protocol = $current_protocol === 'https' ? 'http' : 'https';
			$to_protocol = $from_protocol === 'http' ? 'https' : 'http';

			return preg_replace("/^".$from_protocol.":/i", $to_protocol.':', $url);

		}

		$request_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		while ( have_rows($redirections_field, get_options_page_id()) ){

			the_row();

			$from_url = get_changed_protocol_url(get_sub_field('url_redirect_from'));

			if ( $request_url == $from_url ){
				wp_redirect(get_sub_field('url_redirect_to'));
				exit;
			}
		}
	}
}
add_action('init','redirect_pages');