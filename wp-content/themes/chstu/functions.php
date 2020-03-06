<?php

function get_functions_path(){
	return dirname(__FILE__).'/inc/functions';
}

require_once get_functions_path().'/required-plugins.php';
require_once get_functions_path().'/wpml.php';
require_once get_functions_path().'/base.php';
require_once get_functions_path().'/create-default-structure.php';
require_once get_functions_path().'/acf-groups/acf-groups.php';
require_once get_functions_path().'/breadcrumbs.php';
require_once get_functions_path().'/favicons-and-pwa.php';

function get_blog_name(){
	$blog_name = get_field('site_name', get_options_page_id());
	if ( empty($blog_name) ){
		$blog_name = get_bloginfo('name');
	}
	return $blog_name;
}

function get_blog_description(){

	$blog_descr = get_field('site_descr', get_options_page_id());

	if ( empty($blog_descr) ){
		$blog_descr = get_bloginfo('description');
	}

	return $blog_descr;
}

// Custom title
function get_custom_wp_title(){

	$blog_name = get_blog_name();

	if ( is_home() || is_front_page() ) {
		return $blog_name;
	}
	else if ( is_category() && !empty($tag_names = get_tag_names_string()) ){
		return $blog_name.wp_title(' » ', false).': '.$tag_names;
	}
	else {
		return $blog_name.wp_title(' » ', false);
	}
}

function get_tag_names_string(){

	if ( !empty($_GET['tag']) ){

		$tags = $_GET['tag'];
		$tag_slug_list = explode(',', $tags);
		$tag_names = '';
		$position = 0;

		foreach ( $tag_slug_list as $tag_slug ){
			$tag = get_term_by('slug', $tag_slug, 'post_tag');
			$tag_names .= $tag->name;
			if ( $position < sizeof($tag_slug_list) - 1 ){
				$tag_names .= ', ';
			}
			$position += 1;
		}

		return $tag_names;
	}
}

function custom_wp_title(){
	echo '<title>'.htmlentities(get_custom_wp_title()).'</title>';
}


// Meta description
function custom_wp_meta_description(){

	$meta_descr = '';

	if ( is_home() || is_front_page() ){
		$meta_descr = get_blog_description();
	}
	else if ( is_category() ){

		$category = get_queried_object();
		$category_descr = $category->category_description;
		$meta_descr = $category_descr;

		if ( is_tag() && !empty($tag_names = get_tag_names_string()) ){

			$sub_descr = get_localization_string('By categories').': ';

			if ( !empty($meta_descr) ){
				$meta_descr .= ' '.$sub_descr.$tag_names.'.';
			} else {
				$meta_descr = $sub_descr.$tag_names.'.';
			}
		}

	}
	else {
		$meta_descr = get_field('meta_description');
	}

	if ( !empty($meta_descr) ){
		$meta_descr = htmlentities($meta_descr);
		$meta_descr_html = '<meta property="og:description" content="'.$meta_descr.'">';
		$meta_descr_html .= '<meta name="description" content="'.$meta_descr.'">';
		echo $meta_descr_html;
	}
}


// Custom code in footer
function custom_wp_footer_code(){
	if (!empty($add_code_footer = get_field('add_code_footer', get_options_page_id()))){
		echo $add_code_footer;
	}
}
add_action('wp_footer','custom_wp_footer_code');


// Custom code in header
function custom_wp_header_code(){
	if (!empty($add_code_header = get_field('add_code_header', get_options_page_id()))){
		echo $add_code_header;
	}
}


// Custom image for media
function custom_image_for_media(){
	if (is_front_page() || is_singular( 'vacancies' )){
		$image_url = get_bloginfo('template_url').'/assets/media.jpg';
		echo
			'<meta property="og:image" content="'.$image_url.'">'.
			'<link rel="image_src" href="'.$image_url.'">';
	} else if ( is_singular( 'our_life' )) {
        $image_url = get_field('background_image', get_the_ID())['sizes']['large'];
		echo
			'<meta property="og:image" content="'.$image_url.'">'.
			'<link rel="image_src" href="'.$image_url.'">';
  }
}


function custom_wp_head(){
	custom_wp_title();
	custom_wp_meta_description();
	custom_wp_favicon();
	custom_image_for_media();
	custom_wp_header_code();
}
add_action('wp_head','custom_wp_head');


// Google reCaptcha
function captcha_enabled(){
	return get_field('form_captcha_state', get_options_page_id());
}


// Disable styles for WP Multilang plugin
function exclude_wpml_styles(){
	if (wp_style_is('wpm-main')){
		wp_dequeue_style('wpm-main');
	}
}


// Include resources
function include_resources(){
	$bundle_dir = get_template_directory_uri().'/bundle/';
	wp_deregister_script('jquery');
	wp_register_script('index-scripts', $bundle_dir.'index.js');
	wp_enqueue_script('index-scripts');
	wp_register_style('index-styles', $bundle_dir.'index.css');
	wp_enqueue_style('index-styles');
	wp_dequeue_style('wp-block-library'); // Disable Gutenberg CSS
	exclude_wpml_styles();

//	if ( is_category() ){
//		wp_register_script('category-scripts', $bundle_dir.'category.js');
//		wp_enqueue_script('category-scripts');
//		wp_register_style('category-styles', $bundle_dir.'category.css');
//		wp_enqueue_style('category-styles');
//	}
//	else if ( is_single() ){
//
//		wp_register_script('single-scripts', $bundle_dir.'single.js');
//		wp_enqueue_script('single-scripts');
//		wp_register_style('single-styles', $bundle_dir.'single.css');
//		wp_enqueue_style('single-styles');
//
//		if ( in_category('vacancies') ){
//			wp_register_script('vacancy-scripts', $bundle_dir.'vacancy.js');
//			wp_enqueue_script('vacancy-scripts');
//			wp_register_style('vacancy-styles', $bundle_dir.'vacancy.css');
//			wp_enqueue_style('vacancy-styles');
//		}
//
//	}
//	else if ( is_page() && !is_front_page() ){
//
//		wp_register_script('page-scripts', $bundle_dir.'page.js');
//		wp_enqueue_script('page-scripts');
//		wp_register_style('page-styles', $bundle_dir.'page.css');
//		wp_enqueue_style('page-styles');
//
//		if ( is_default_page('our-team') ){
//			wp_register_script('our-team-scripts', $bundle_dir.'our-team.js');
//			wp_enqueue_script('our-team-scripts');
//			wp_register_style('our-team-styles', $bundle_dir.'our-team.css');
//			wp_enqueue_style('our-team-styles');
//		}
//		else if ( is_page_template('page-office.php') ){
//			wp_register_script('locations-scripts', $bundle_dir.'locations.js');
//			wp_enqueue_script('locations-scripts');
//			wp_register_style('office-styles', $bundle_dir.'office.css');
//			wp_enqueue_style('office-styles');
//		}
//		else if ( is_default_page('services') ){
//			wp_register_script('services-scripts', $bundle_dir.'services.js');
//			wp_enqueue_script('services-scripts');
//			wp_register_style('services-styles', $bundle_dir.'services.css');
//			wp_enqueue_style('services-styles');
//		}
//
//	}
}
add_action('wp_enqueue_scripts', 'include_resources');


// Include SVG in template
function inline_template_svg($file){
	echo file_get_contents(get_template_directory_uri().'/assets/images/'.$file.'.svg');
}


// Include SVG by URL
function inline_svg_by_url($file){
	echo file_get_contents($file);
}


function clear_tel($tel){
	return str_replace(array(' ','(',')','-','.'), '', $tel);
}


// Home URL or #home
function home_link(){
	if (is_front_page()){
		echo '#home';
	} else {
		echo esc_url(home_url('/'));
	}
}


// Field wrapper
function get_field_by_type($name, $type, $post_id){
	if ($type === 'field'){
		if (!empty($post_id)){
			return get_field($name, $post_id);
		} else {
			return get_field($name);
		}
	} else if ($type === 'subfield'){
		if (!empty($post_id)){
			return get_sub_field($name, $post_id);
		} else {
			return get_sub_field($name);
		}
	}
}
function get_field_with_wrapper($html_start, $name, $type, $post_id, $html_end){
	if ( !empty($field = get_field_by_type($name, $type, $post_id)) ){
		return $html_start.$field.$html_end;
	}
}
function the_field_with_wrapper($html_start, $name, $type, $post_id, $html_end){
	echo get_field_with_wrapper($html_start, $name, $type, $post_id, $html_end);
}

//function get_front_page_blocks(){
//
//	function get_block_weight($acf_config_name){
//		$weight = get_field($acf_config_name);
//		if (empty($weight)){
//			$weight = 0;
//		}
//		return $weight;
//	}
//
//	$blocks_names_list = [
//		[
//			'php_module_name' => 'vacancies',
//			'weight' => get_block_weight('fp_vacancies_weight')
//	    ],
//		[
//			'php_module_name' => 'services',
//			'weight' => get_block_weight('fp_services_weight')
//	    ],
//		[
//			'php_module_name' => 'benefit',
//			'weight' => get_block_weight('fp_benefit_weight')
//	    ],
//		[
//			'php_module_name' => 'founders',
//			'weight' => get_block_weight('fp_founders_weight')
//		],
//	    [
//			'php_module_name' => 'offices',
//			'weight' => get_block_weight('fp_offices_weight')
//	    ],
//		[
//			'php_module_name' => 'cta',
//			'weight' => get_block_weight('fp_cta_weight')
//		],
//	    [
//			'php_module_name' => 'our-life',
//			'weight' => get_block_weight('fp_our-life_weight')
//	    ]
//	];
//
//	usort($blocks_names_list, function($a, $b){
//		return $b['weight'] - $a['weight'];
//	});
//
//	foreach ($blocks_names_list as &$block) {
//		get_template_part('template_parts/front-page/'.$block['php_module_name']);
//	}
//}


function get_rest_api_route(){
	return rtrim(get_bloginfo('url'), '/').'/wp-json/acf/v3';
}

//function get_category_id_by_slug($slug = false){
//	if ($slug){
//		//return get_category_by_slug($slug)->cat_ID;
//		return get_term_by('slug', $slug, 'category')->term_id;
//	} else {
//		$category = get_queried_object();
//		return $category->cat_ID;
//	}
//}

function get_current_category_id(){
	if ( !empty($category = get_queried_object()) ){
		return $category->cat_ID;
	}
}


function get_acf_field_key( $field_name, $post_id ) {
	global $wpdb;
	$acf_fields = $wpdb->get_results( $wpdb->prepare( "SELECT ID,post_parent,post_name FROM $wpdb->posts WHERE post_excerpt=%s AND post_type=%s" , $field_name , 'acf-field' ) );
	// get all fields with that name.
	switch ( count( $acf_fields ) ) {
		case 0: // no such field
			return false;
		case 1: // just one result.
			return $acf_fields[0]->post_name;
	}
	// result is ambiguous
	// get IDs of all field groups for this post
	$field_groups_ids = array();
	$field_groups = acf_get_field_groups( array(
		'post_id' => $post_id,
	) );
	foreach ( $field_groups as $field_group )
		$field_groups_ids[] = $field_group['ID'];

	// Check if field is part of one of the field groups
	// Return the first one.
	foreach ( $acf_fields as $acf_field ) {
		if ( in_array($acf_field->post_parent,$field_groups_ids) )
			return $acf_field->post_name;
	}
	return false;
}


function the_vacancies_filter(){

	function get_vacancies_categories_select_html(){
		$html = '';
		$vacancies_categories = get_terms([
			'taxonomy' => 'vacancy_cat',
			'hide_empty' => false
		]);
		if (!empty($vacancies_categories)){
			$html = '
				<select class="select select_narrow select_style_light" name="vacancy_cat">
					<option value selected>'.get_localization_string('All categories').'</option>';

			foreach ($vacancies_categories as $vacancies_category){
				$html .= '<option value="'.$vacancies_category->slug.'">'.$vacancies_category->name.'</option>';
			}
			$html .= '</select>';
		}
		return $html;
	}

	function get_vacancies_cities_select_html(){

		$html = '';

		if ( !empty($acf_field_key = get_acf_field_key('vacancy_city', null)) ){

			if (
				!empty($cities_obj = get_field_object($acf_field_key)) &&
			    !empty($cities_arr = $cities_obj['choices'])
			){
				$html = '<select class="select select_narrow select_style_light" name="vacancy_city">
					<option value selected>'.get_localization_string('All cities').'</option>';
				foreach ($cities_arr as $key=>$city){
					$html .= '<option value="'.$key.'">'.get_localization_string($city).'</option>';
				}
				$html .= '</select>';
			}
		}

		return $html;
	}

	function get_vacancies_hot_select_html(){
		return '
			<select class="select select_narrow select_style_light" name="vacancy_hot">
				<option value selected>'.get_localization_string('All priorities').'</option>
				<option value="false">'.get_localization_string('Normal').'</option>
				<option value="true">'.get_localization_string('Hot').'</option>
			</select>';
	}

	$form_action = get_rest_api_route().'/vacancies';

		echo '
		<form 
			id="vacancies-filter"
			class="vacancies-filter" 
			action="'.$form_action.'"
			data-category-url="'.get_current_category_link().'"
		>
			<div class="vacancies-filter__inner-wrapper">
			'.get_vacancies_categories_select_html().
	          get_vacancies_cities_select_html().
	          get_vacancies_hot_select_html().'
			</div>
			<button type="submit" hidden></button>
		</form>';
}


function the_show_more_posts_button($rest_path_prefix){
	$rest_api_category_action = get_rest_api_route().$rest_path_prefix;
	echo '
		<button 
			class="show-more__button button button_lg button_style_dark-light button_hover_dark"
	        data-action="'.$rest_api_category_action.'"
	    >
	        '.get_localization_string('Show more').'
	    </button>
    ';
}

//function get_current_category_link(){
//	if ( !empty($category_id = get_current_category_id()) ){
//		return get_category_link($category_id);
//	}
//}

function the_single_nav($prev_str, $next_str){

	if (!$prev_str) $prev_str = get_localization_string('Previous');
	if (!$next_str) $next_str = get_localization_string('Next');

	$prev_post_obj = get_adjacent_post('', '', true);
	$next_post_obj = get_adjacent_post('', '', false);

	$prev_post_ID = isset( $prev_post_obj->ID ) ? $prev_post_obj->ID : '';
	$next_post_ID = isset( $next_post_obj->ID ) ? $next_post_obj->ID : '';

	$prev_post_link = get_permalink( $prev_post_ID );
	$next_post_link = get_permalink( $next_post_ID );

	echo '
		<nav class="single-nav site-block site-block_light">
			<div class="single-nav_inner-wrapper">
				<a href="'.$prev_post_link.'" rel="prev" class="button button_sm button_style_dark-light button_hover_dark single-nav__link">'.$prev_str.'</a>
		        <a href="'.$next_post_link.'" rel="next" class="button button_sm button_style_dark-light button_hover_dark single-nav__link">'.$next_str.'</a>
	    	</div>
	    </nav>';
}


function the_media_links(){

	$post_link = get_post_permalink();

	if ($post_link){

		$post_encoded_link = urlencode($post_link);

		$tw_link = 'https://twitter.com/intent/tweet?text=OArm&url='.$post_encoded_link;
		$li_link = 'https://www.linkedin.com/cws/share?url='.$post_encoded_link;
		$fb_link = 'https://www.facebook.com/share.php?u='.$post_encoded_link;

		function get_media_button($media_link, $media_class_prefix, $media_name){
			return '
				<a href="'.$media_link.'" 
				   target="_blank" 
				   rel="nofollow noreferrer noopener" 
				   class="media-links__item media-links__item_'.$media_class_prefix.' button button_sm button_style_brand-fill button_hover_dark">
				   <span>'.$media_name.'</span>
			    </a>';
		}

		$copy_to_clipboard = '
		<div class="media-links__item media-links__item_cb button button_sm button_style_brand-fill button_hover_dark" data-copied-success="'.get_localization_string('Link copied to clipboard').'">'.
		                     '<span>'.get_localization_string('Copy link').'<span>'.
		                     '</div>';

		echo '<div class="media-links">'.
		     get_media_button($tw_link, 'tw', 'Twitter').
		     get_media_button($li_link, 'li', 'LinkedIn').
		     get_media_button($fb_link, 'fb', 'Facebook').
		     $copy_to_clipboard.
		     '</div>';

	}
}


//function get_filter_tags_by_group($group_name){
//
//	global $wpdb;
//	$terms_by_group = $wpdb->get_results("
//		SELECT term_id, slug, name
//		FROM wp_terms
//		WHERE term_id
//		IN (
//		SELECT term_id
//		FROM wp_termmeta
//		WHERE meta_key = 'tag-category'
//		AND meta_value = '".$group_name."'
//		)
//	");
//
//	// Wrong result with WPML
//	/*$terms_by_group = get_terms([
//		'taxonomy' => 'post_tag',
//		'hide_empty' => false,
//		//'get' => 'all',
//		'meta_query' => [[
//			'key' => 'tag-category',
//			'value' => $group_name
//		]],
//	]);*/
//
//	return $terms_by_group;
//}


//function get_tags_by_group($post_tags, $group_name){
//
//	if (
//		!empty($post_tags) &&
//		!empty($filter_tags_by_group = get_filter_tags_by_group($group_name))
//	){
//
//		$filter_tags_from_group = [];
//		$filter_tags_slug_list = [];
//
//		foreach ($filter_tags_by_group as $filter_tag_by_group){
//			$filter_tags_slug_list[] = $filter_tag_by_group->slug;
//		}
//
//		foreach ($post_tags as $post_tag){
//			if ( in_array($post_tag->slug, $filter_tags_slug_list) ){
//				$filter_tags_from_group[] = $post_tag;
//			}
//		}
//
//		return $filter_tags_from_group;
//	}
//}

//function get_grouped_tags($tags){
//	$tags_categories = get_filter_names();
//	$tags_in_categories = [];
//	foreach ( $tags_categories as $tags_category ){
//
//		$tags_by_group = get_tags_by_group($tags, $tags_category);
//
//		$tags_in_categories[] = [
//			'tag_category_slug' => $tags_category,
//			'tag_category_name' => get_field('filter_'.$tags_category, get_options_page_id()),
//			'tags' => $tags_by_group
//		];
//	}
//	return $tags_in_categories;
//}


//function get_grouped_tags_of_current_post(){
//	$post_tags = get_the_tags();
//	return get_grouped_tags($post_tags);
//}


//function get_translated_category_link($slug){
//	if ( !empty($queried_category_id = get_translated_cat_by_slug($slug)) ){
//		return get_category_link($queried_category_id->term_id);
//	}
//}


function query_front_page_and_show_block($blockFunction){
	if (
		!empty($default_front_page = get_translated_page_by_path('front-page')) &&
		(
			function_exists('icl_object_id') &&
			!empty($translated_front_page_id = icl_object_id($default_front_page->ID, 'page', true, ICL_LANGUAGE_CODE))
		)
	){
		$front_page_query = new WP_Query('p='.$translated_front_page_id.'&post_type=page');
		if ($front_page_query->have_posts()){
			$front_page_query->the_post();
			call_user_func($blockFunction);
		}
		wp_reset_postdata();
	}
}


//function get_translated_page_id_by_slug($page_slug){
//
//	if ( !empty($queried_page = get_page_by_path($page_slug)) ) {
//		$translated_page_id = null;
//		if ( function_exists('icl_object_id') ) {
//			$translated_page_id = icl_object_id( $queried_page->ID, 'page', true, ICL_LANGUAGE_CODE );
//		}
//		if ( empty($translated_page_id) ){
//			$translated_page_id = $queried_page->ID;
//		}
//		return (int)$translated_page_id;
//	}
//}


//function get_page_link_by_slug($page_slug){
//	return get_the_permalink(get_translated_page_id_by_slug($page_slug));
//}
//
//
//function the_page_link_by_slug($page_slug){
//	echo get_page_link_by_slug($page_slug);
//}


// Links
function get_domain_from_url($input, $clear_www){
	$input = trim($input, '/');
	if (!preg_match('#^http(s)?://#', $input)) {
		$input = 'http://' . $input;
	}
	$urlParts = parse_url($input);
	if ($clear_www){
		return preg_replace('/^www\./', '', $urlParts['host']);
	} else {
		return $urlParts['host'];
	}
}
function get_project_domain($clear_www){
	$domain = $_SERVER['HTTP_HOST'];
	if ($clear_www){
		return preg_replace('/^www\./', '', $domain);
	} else {
		return $domain;
	}
}
function get_app_name(){
	if ( !empty($app_name = get_field('app_name', get_options_page_id())) ) {
		return $app_name;
	} else {
		return get_blog_name();
	}
}


// Vacancies get functions for REST endpoint

function get_vacancies($params){

	$query_params = [
		'post_type' => 'post',
		'category_name' => 'vacancies'
	];

	if ( !empty($params) ) {

		if ( is_object($params) ) $params = $params->get_params();

		if ( ! empty( $params['paged'] ) && $params['paged'] > 0 ) $query_params['paged'] = $params['paged'];

		$meta_query_params = [];

		if ( ! empty( $params['vacancy_cat'] ) ) {
			$vacancy_cat_obj = get_term_by( 'slug', $params['vacancy_cat'], 'vacancy_cat' );
			$vacancy_cat_id  = $vacancy_cat_obj->term_id;
			if ( $vacancy_cat_id ) {
				array_push( $meta_query_params, [
					'key'   => 'vacancy_cat',
					'value' => $vacancy_cat_id
				] );
			}
		}

		if ( ! empty( $params['vacancy_city'] ) ) {
			array_push( $meta_query_params, [
				'key'   => 'vacancy_city',
				'value' => $params['vacancy_city']
			] );
		}

		if ( array_key_exists('vacancy_hot', $params) ){
			$vacancy_hot = filter_var($params['vacancy_hot'], FILTER_VALIDATE_BOOLEAN );
			$hot_param = [
				'key' => 'vacancy_hot',
				'value' => true
			];
			if ( $vacancy_hot === false ) $hot_param['compare'] = '!=';
			array_push($meta_query_params, $hot_param);
		}

		if ( !empty($meta_query_params) ) $query_params['meta_query'] = $meta_query_params;
	}

	$query = new WP_Query($query_params);

    if ( $query->have_posts() ){
	    $vacancies = array();
	    while ( $query->have_posts() ) {
		    $query->the_post();
			$vacancy = [
				'title' => get_the_title(),
				'permalink' => get_permalink(),
				'vacancy_cat' => get_field('vacancy_cat')->name,
				'vacancy_city' => get_field('vacancy_city')['label'],
				'vacancy_hot' => get_field('vacancy_hot')
			];
			array_push($vacancies, $vacancy);
	    }
	    wp_reset_postdata();
	    return $vacancies;
    }
}

function the_captcha(){
	if ( captcha_enabled() ){
		$url = get_bloginfo('template_url').'/inc/mails/captcha/captcha.php';
		echo '
            <div class="input-wrapper">
                <div class="form__captcha">
                    <img class="form__captcha-img"
                         src="'.$url.'"
	                     data-src="'.$url.'"
                         alt="Captcha"
                    >
                    <input class="input"
                           type="text"
                           name="captcha"
                           maxlength="5"
                           pattern="[0-9]{5}"
                           data-post-required="true"
                    >
                </div>
            </div>
		';
	}
}


// Register REST Vacancies endpoint

add_action('rest_api_init', function () {
	register_rest_route(
		'acf/v3',
		'/vacancies/',
		[
			'methods' => 'GET',
			'callback' => 'get_vacancies',
			'permission_callback' => null,
			'args' => [
				'vacancy_cat' => [
					'default' => null,
					'required' => null
				],
				'vacancy_city' => [
					'default' => null,
					'required' => null
				],
				'vacancy_hot' => [
					'default' => null,
					'required' => null
				],
				'paged' => [
					'default' => null,
					'required' => null
				]
			]
		]
	);
});


add_action('init', function (){
	register_taxonomy(
		'vacancy_cat',
		array('post'),
		array(
			'label' => 'Категории вакансий',
			'hierarchical' => false,
			'show_ui' => true,
			'show_in_rest' => true,
			'query_var' => true,
			'show_in_quick_edit' => false,
			'meta_box_cb' => false,
		)
	);
}, 0);

class SH_Nav_Menu_Walker extends Walker {
	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

//	function start_lvl(&$output, $depth = 0, $args = array()) {
//		$indent = str_repeat("\t", $depth);
//		$output .= "\n$indent";
//		//$output .= "<i class=\"dropdown icon\"></i>\n";
//		$output .= "<div class=\"menu\">\n";
//	}
//
//	function end_lvl(&$output, $depth = 0, $args = array()) {
//		$indent = str_repeat("\t", $depth);
//		$output .= "$indent</div>\n";
//	}

	function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes = in_array( 'current-menu-item', $classes ) ? array( 'menu__item', 'menu__item_current' ) : array('menu__item');
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = strlen( trim( $class_names ) ) > 0 ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		$item_output = $args->before;
		$item_output .= '<li'.$class_names.'><a'. $attributes . '>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= "</a></li>\n";
		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}