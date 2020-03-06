<?php

require_once dirname(__FILE__).'/../../inc/tgm-plugin-activation/class-tgm-plugin-activation.php';

function prefix_register_plugins() {

	$plugins_path = get_template_directory_uri().'/inc/tgm-plugin-activation/plugins';

	$plugins = array(
		array(
			'name'               => 'WPML Multilingual CMS',
			'slug'               => 'sitepress-multilingual-cms',
			'source'             => $plugins_path.'/sitepress-multilingual-cms.4.3.10.zip',
			'required'           => true,
			'force_activation'   => true
		),
		array(
			'name'      => 'Classic Editor',
			'slug'      => 'classic-editor',
			'required'  => true,
			'force_activation'   => true,
			//'is_callable' => ''
		),
		array(
			'name'               => 'Advanced Custom Fields PRO',
			'slug'               => 'advanced-custom-fields-pro',
			'source'             => $plugins_path.'/advanced-custom-fields-pro-5.8.7.zip',
			'required'           => true,
			'force_activation'   => true,
			'is_callable'        => 'acf_add_local_field_group',
		),
		array(
			'name'              => 'ACF to REST API',
			'slug'              => 'acf-to-rest-api',
			'required'          => true,
			'force_activation'  => true,
		),
		array(
			'name'               => 'Advanced Custom Fields Multilingual',
			'slug'               => 'acfml',
			'source'             => $plugins_path.'/acfml.1.6.1.zip',
			'required'           => true,
			'force_activation'   => true
		),
		array(
			'name'              => 'Safe SVG',
			'slug'              => 'safe-svg',
			'required'          => true,
			'force_activation'  => true,
		),
		array(
			'name'      => 'Limit Login Attempts Reloaded',
			'slug'      => 'limit-login-attempts-reloaded',
			'required'  => false,
		),
		array(
			'name'      => 'BackWPup',
			'slug'      => 'backwpup',
			'required'  => false,
		),
		/*array(
			'name'      => 'Admin Menu Editor',
			'slug'      => 'admin-menu-editor',
			'required'  => false,
		),
		array(
			'name'      => 'Health Check & Troubleshooting',
			'slug'      => 'health-check',
			'required'  => false,
		),*/
		array(
			'name'      => 'Google XML Sitemaps',
			'slug'      => 'google-sitemap-generator',
			'required'  => false,
        ),
//	    array(
//			'name'               => 'WPZOOM Social Feed Widget',
//			'slug'               => 'instagram-widget-by-wpzoom',
//			'source'             => $plugins_path.'/instagram-widget-by-wpzoom.zip',
//			'required'           => true,
//			'force_activation'   => true
//		)
	);

	$config = array(
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php', // Parent menu slug.
		'capability'   => 'manage_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true, // Show admin notices or not.
		'dismissable'  => false,
		'is_automatic' => true, // Automatically activate plugins after installation or not.
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'prefix_register_plugins' );