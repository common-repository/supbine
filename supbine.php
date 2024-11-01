<?php
/*
 * Plugin Name: Supbine
 * Version: 1.0
 * Plugin URI: https://widget.supbine.com/
 * Description: Supbine Widget integration for Wordpress.
 * Author: Christoffer Mikkelsen (Zahlio) CTO @ Supbine
 * Author URI: https://supbine.com/
 */

define( 'SUPBINE_VERSION_NUMBER', "1.0.0" );

require_once dirname( __FILE__ ) . '/accountconfig.php';

function SUPBINE_load_style() {
	wp_register_style( 'supbine_style', plugins_url( 'supbine/supbine.css'));
	wp_enqueue_style( 'supbine_style' );
}

function SUPBINE_add_caps() {
	$role = get_role( 'administrator' );
	$role->add_cap( 'access_supbine' );
}

add_action( 'admin_enqueue_scripts', 'SUPBINE_load_style' );
add_action( 'admin_init', 'SUPBINE_add_caps' );

function SUPBINE_inject(){
	$companyId = get_option('supbine_companyId', null);
	$locale = get_option('supbine_locale', "en_US");

	if ($companyId == null){
		return;
	}

	echo "<!--Start of Supbine Wordpress Script v" . SUPBINE_VERSION_NUMBER . "-->";
	echo "<script src=\"//widget.supbine.com/js/main.js\"></script>";
	echo "<script>SupbineWidget.init({companyId: " . esc_textarea($companyId) . ", locale: '" . esc_textarea($locale) . "'});</script>";
	echo "<!--End of Supbine Wordpress Script-->";
}

function SUPBINE_create_menu() {
	add_menu_page( 
		__( 'Account Configuration', 'supbine' ),
		__( 'Supbine', 'supbine' ),
		'access_supbine',
		'supbine',
		'SUPBINE_account_config',
		plugins_url( 'supbine/images/sidebar-logo.png')
	);
	
	add_action( 'admin_menu', 'register_plugin_settings' );
}

function SUPBINE_register_plugin_settings() {
	register_setting( 'supbine-settings-group', 'supbine_companyId' );
	register_setting( 'supbine-settings-group', 'supbine_locale' );
}

add_action( 'wp_footer', 'SUPBINE_inject' );
add_action( 'admin_menu', 'SUPBINE_create_menu' );