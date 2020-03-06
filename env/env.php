<?php

function get_config_file_name($env_prefix){
	return 'wp-config_'.$env_prefix.'.php';
}

// Config for localhost (unique and local for each developer)
// Add to .gitignore
$config_name_local = get_config_file_name('local'); 

// Config for dev server
// Exclude from pipelines and prod
$config_name_dev = get_config_file_name('dev'); 

// Config for Green
// May not be exist
$config_name_pipeline_green = get_config_file_name('pipeline-green'); 

// Config for Blue
// May not be exist
// After testing change on $config_name_prod and remove file config
$config_name_pipeline_blue = get_config_file_name('pipeline-blue'); 

// Config for prod server
// Always exist 
$config_name_prod = get_config_file_name('prod'); 

$current_domain = preg_replace('/www\./i', '', $_SERVER['SERVER_NAME']);
$green_state = stripos($current_domain, 'green.');
$blue_state = stripos($current_domain, 'blue.');


if ( file_exists(__DIR__.'/'.$config_name_local) ) {
	require_once($config_name_local);
	define('ENVIRONMENT', 'local');
} 

else if ( file_exists(__DIR__.'/'.$config_name_dev) ){
	require_once($config_name_dev);
	define('ENVIRONMENT', 'dev');
}

else if ( $green_state ){
	require_once($config_name_pipeline_green);
	define('ENVIRONMENT', 'pipeline-green');
} 

else if ( $blue_state ){
	require_once($config_name_pipeline_blue);
	define('ENVIRONMENT', 'pipeline-blue');
} 

else if ( file_exists(__DIR__.'/'.$config_name_prod) ){
	require_once($config_name_prod);
	define('ENVIRONMENT', 'prod');
} 


if ( !defined('ENVIRONMENT') ){
	exit('No сonfiguration for this host!');
}