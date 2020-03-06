<?php

global $sitepress;
remove_action( 'wp_head', array( $sitepress, 'meta_generator_tag' ) );

function get_default_lang_code(){
	global $sitepress;
	return $sitepress->get_default_language();
}

function get_url_with_lang_autoprefix($url, $path_keyword){

	$default_lang_code = get_default_lang_code();

	if ( ICL_LANGUAGE_CODE !== $default_lang_code ){
		$replaceWith = ICL_LANGUAGE_CODE.'/'.$path_keyword;
		$pos = strpos($url, $path_keyword);
		if ($pos !== false) $url = substr_replace($url, $replaceWith, $pos, strlen($path_keyword));
	}

	return $url;
}

function get_url_with_lang_prefix($url, $path_keyword, $lang_prefix){

	$default_lang_code = get_default_lang_code();

	if ( $lang_prefix !== $default_lang_code ){
		$replaceWith = $lang_prefix.'/'.$path_keyword;
		$pos = strpos($url, $path_keyword);
		if ($pos !== false) $url = substr_replace($url, $replaceWith, $pos, strlen($path_keyword));
	}

	return $url;
}

function get_current_category_link(){
	if ( !empty($category_id = get_current_category_id()) ){
		return get_url_with_lang_autoprefix(get_category_link($category_id), 'category');
	}
}

function get_multilang_category_link_by_slug($slug){
	$cat = get_category_by_slug($slug);
	$cat_link = get_category_link($cat->term_id);
	return get_url_with_lang_autoprefix($cat_link, 'category');
}

function get_multilang_category_link($cat_id){
	$cat_link = get_category_link($cat_id);
	return get_url_with_lang_autoprefix($cat_link, 'category');
}

function get_cat_multilang_field($field_name, $cat_id){
	$default_lang_code = get_default_lang_code();
	$current_lang = ICL_LANGUAGE_CODE;
	if ( $current_lang !== $default_lang_code ){
		$cat_translate = get_field('category-translate', 'category_'.$cat_id);
		return $cat_translate[$field_name.'_'.$current_lang];
	} else {
		if ( $field_name === 'name' ){
			return get_cat_name($cat_id);
		} else if ( $field_name === 'description' ){
			$cat = get_term_by('id', $cat_id, 'category');
			return $cat->description;
		}
	}
}

// WPML language switcher

function wpml_custom_language_switcher(){
	if ( function_exists('icl_object_id') ){
		$lang_switcher = icl_get_languages("skip_missing=0");
		$html = '<ul class="language-switcher fade-in">';
		foreach ($lang_switcher as $lang) {
			$lang_code = $lang['code'];
			if ($lang_code == 'uk') $lang_code = 'ua';
			$item_inner_html = $lang_code;
			if ( $lang['active'] != true ){
				$link = $lang['url'];
				if ( is_category() ){
					$cat = get_queried_object();
					$link = wpml_category_permalink($cat->term_id, $lang['code']);
				}
				$item_inner_html = '<a class="language-switcher__link" href="'.$link.'">'.$lang_code.'</a>';
			}
			$item_class = 'language-switcher__item';
			if ( $lang['active'] == true ) $item_class .= ' '.$item_class.'_active';
			$html .= '<li class="'.$item_class.'">'.$item_inner_html.'</li>';
		}
		$html .= '</ul>';
		echo $html;
	}
}

function wpml_category_permalink($cat_id, $lang_code = ICL_LANGUAGE_CODE){
	$link = get_category_link($cat_id);
	if ( $link ) return apply_filters('wpml_permalink', $link, $lang_code);
}

function get_default_cat_id($cat_id){
	return icl_object_id($cat_id, 'category', true, wpml_get_default_language());
}

//function is_default_category($slug){
//
//	if ( !empty($queried_cat = get_queried_object()) ){
//
//		$default_queried_cat_id = get_default_cat_id($queried_cat->term_id);
//
//		if ( !empty($checked_cat = get_term_by('slug', $slug, 'category')) ){
//
//			$default_checked_cat_id = get_default_cat_id($checked_cat->term_id);
//
//			if ( $default_queried_cat_id === $default_checked_cat_id){
//				return true;
//			}
//		}
//	}
//}

function get_default_page_id($page_id){
	if ( function_exists('icl_object_id') ){
		return icl_object_id($page_id, 'page', true, wpml_get_default_language());
	} else {
		return $page_id;
	}
}

function is_default_page($slug){

	if ( !empty($queried_page = get_queried_object()) ){

		$default_queried_page_id = get_default_page_id($queried_page->ID);

		if ( !empty($checked_page_id = get_translated_page_id_by_slug($slug)) ){

			$default_checked_page_id = get_default_page_id($checked_page_id);

			if ( $default_queried_page_id === $default_checked_page_id ){
				return true;
			}
		}
	}
}

function is_default_parent_page($slug){
	global $post;
	return $post->post_parent == get_translated_page_id_by_slug($slug);
}

function get_translated_page_by_path($slug){

	if (
		!empty($queried_page = get_page_by_path($slug)) &&
		!empty($translated_page_id = icl_object_id($queried_page->ID, 'page', true, ICL_LANGUAGE_CODE))
	){
		return get_post($translated_page_id);
	}
}

function get_translated_page_id_by_slug($page_slug){
	if ( !empty($queried_page = get_page_by_path($page_slug)) ) {
		$translated_page_id = null;
		if ( function_exists('icl_object_id') ) {
			$translated_page_id = icl_object_id( $queried_page->ID, 'page', true, ICL_LANGUAGE_CODE );
		}
		if ( empty($translated_page_id) ){
			$translated_page_id = $queried_page->ID;
		}
		return (int)$translated_page_id;
	}
}

//function get_permalink_by_slug($slug){
//	return get_page_link(get_page_by_path($slug)->ID);
//}
//
//function the_permalink_by_slug($slug){
//	echo get_permalink_by_slug($slug);
//}

function get_translated_permalink_by_slug($slug){
	return get_the_permalink(get_translated_page_id_by_slug($slug));
}

function the_translated_permalink_by_slug($slug){
	echo get_translated_permalink_by_slug($slug);
}