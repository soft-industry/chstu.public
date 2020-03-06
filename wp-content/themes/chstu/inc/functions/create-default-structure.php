<?php

function update_post_status($post_id, $status){
	if ( get_post_status($post_id) !== $status ){
		wp_update_post(array(
			'ID' => $post_id,
			'post_status' => $status
		));
	}
}

function create_page_if_not_exist($post_slug, $post_name, $post_status){

	$page_id = get_translated_page_id_by_slug($post_slug);
//	$page = get_page_by_path($post_slug);
//	$page_id = $page->ID;

	if ( empty($page_id) ){
		wp_insert_post([
			'post_title'    => $post_name,
			'post_status'   => $post_status,
			'post_type'     => 'page',
			'post_author'   => 1,
			'post_name'     => $post_slug
		]);
	}  else {
		update_post_status($page_id, $post_status);
	}
}


function create_category_if_not_exist($cat_slug, $cat_name, $cat_parent_slug){

	$cat = get_term_by('slug', $cat_slug, 'category');

	if ( empty($cat) ){

		$cat_params = [
			'slug' => $cat_slug
		];

		if ( !empty($parent_cat = get_term_by('slug', $cat_parent_slug, 'category')) ){
			$cat_params = array_merge($cat_params, ['parent' => $parent_cat->term_id]);
		}

		wp_insert_term($cat_name, 'category', $cat_params);
	}
}

function create_basic_structures(){

	create_page_if_not_exist('options', get_localization_string('Options'), 'private');
	create_page_if_not_exist('front-page', get_localization_string('Front page'), 'publish');
	if ( !empty($front_page = get_page_by_path('front-page')) ){
		update_option('page_on_front', $front_page->ID);
		update_option('show_on_front', 'page');
	}
	create_page_if_not_exist('cookies', 'Cookies', 'publish');
	create_page_if_not_exist('privacy-policy', 'Privacy Policy', 'publish');
}

add_action('after_setup_theme', 'create_basic_structures');