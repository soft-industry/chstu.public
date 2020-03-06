<?php

function custom_wp_favicon(){
	$fav_icon_dir = get_bloginfo('template_url').'/bundle/';
	$app_name = get_app_name();
	echo
		'
		<link rel="apple-touch-icon" sizes="114x114" href="'.$fav_icon_dir.'apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="'.$fav_icon_dir.'apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="'.$fav_icon_dir.'apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="'.$fav_icon_dir.'apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="'.$fav_icon_dir.'apple-touch-icon-180x180.png">
		<link rel="apple-touch-icon" sizes="57x57" href="'.$fav_icon_dir.'apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="'.$fav_icon_dir.'apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="'.$fav_icon_dir.'apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="'.$fav_icon_dir.'apple-touch-icon-76x76.png">
		<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)" href="'.$fav_icon_dir.'apple-touch-startup-image-320x460.png">
		<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)" href="'.$fav_icon_dir.'apple-touch-startup-image-640x920.png">
		<link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" href="'.$fav_icon_dir.'apple-touch-startup-image-640x1096.png">
		<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="'.$fav_icon_dir.'apple-touch-startup-image-750x1294.png">
		<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 3)" href="'.$fav_icon_dir.'apple-touch-startup-image-1182x2208.png">
		<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 3)" href="'.$fav_icon_dir.'apple-touch-startup-image-1242x2148.png">
		<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 1)" href="'.$fav_icon_dir.'apple-touch-startup-image-748x1024.png">
		<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)" href="'.$fav_icon_dir.'apple-touch-startup-image-1496x2048.png">
		<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 1)" href="'.$fav_icon_dir.'apple-touch-startup-image-768x1004.png">
		<link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)" href="'.$fav_icon_dir.'apple-touch-startup-image-1536x2008.png">
		<link rel="icon" type="image/png" sizes="16x16" href="'.$fav_icon_dir.'favicon-16x16.png">
		<link rel="icon" type="image/png" sizes="32x32" href="'.$fav_icon_dir.'favicon-32x32.png">
		<link rel="manifest" href="'.$fav_icon_dir.'manifest.json">
		<link rel="shortcut icon" href="'.$fav_icon_dir.'favicon.ico">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="apple-mobile-web-app-title" content="'.$app_name.'">
		<meta name="application-name" content="'.$app_name.'">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="'.$fav_icon_dir.'mstile-144x144.png">
		<meta name="msapplication-config" content="'.$fav_icon_dir.'browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
		';
}