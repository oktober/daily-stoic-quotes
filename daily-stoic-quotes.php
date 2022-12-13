<?php
/*
Plugin Name: Daily Stoic Quotes
Description: This is a plugin that displays a daily quote from stoic philosophers in the admin area.
Author: S. Farmer
Version: 1.0.0
Text Domain: dailystoicquotes
Domain Path: /languages
Author URI: https://staciefarmer.com
*/


// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// define constant to the main plugin dir for use in loading other files
define( 'DAILYSTOICQUOTES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


// load text domain - for internationalization
function dailystoicquotes_load_textdomain() {
    load_plugin_textdomain( 'dailystoicquotes', false, plugin_dir_path( __FILE__ ) . 'languages/' );
}
add_action( 'plugins_loaded', 'dailystoicquotes_load_textdomain' );


// if in an admin page
if ( is_admin() ) {
    // add sub-level administrative menu
    function dailystoicquotes_add_sublevel_menu() {
    
         add_submenu_page(
            'options-general.php',
            esc_html__( 'Daily Stoic Quote Settings', 'dailystoicquotes' ),
            esc_html__( 'Daily Stoic Quotes', 'dailystoicquotes' ),
            'manage_options',
            'dailystoicquotes',
            'dailystoicquotes_display_settings_page'
         );
    }
    add_action( 'admin_menu', 'dailystoicquotes_add_sublevel_menu' );
    
    // include admin page dependencies
    require_once plugin_dir_path( __FILE__ ) . 'admin/display-quotes.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-page.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-register.php';
    require_once plugin_dir_path( __FILE__ ) . 'admin/settings-callback.php';
}


// default plugin options
function dailystoicquotes_options_default() {
    return array(
        'data_source'   => 'api'
    );
}


// delete transient on plugin deactivation
function dailystoicquotes_deactivation() {

	if ( ! current_user_can( 'activate_plugins' ) ) return;

	delete_transient( 'daily_stoic_quotes' );

}
register_deactivation_hook( __FILE__, 'dailystoicquotes_deactivation' );