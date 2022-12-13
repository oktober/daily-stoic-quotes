<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// register plugin settings
function dailystoicquotes_register_settings() {
    /*
    register_setting(
        string      $option_group,
        string      $option_name,
        callable    $sanitize_callbak
    );
    */

    register_setting(
        'dailystoicquotes_options',
        'dailystoicquotes_options',
        'dailystoicquotes_validation_options'
    );

    /*
    add_settings_section(
        string      $id,
        string      $title,
        callable    $callback,
        string      $page
    );
    */

    add_settings_section(
        'dailystoicquotes_section',
		esc_html__('Customize Login Page', 'dailystoicquotes'), 
        'dailystoicquotes_callback_section',
        'dailystoicquotes'
    );

    /*
    add_settings_field(
        string      $id,
        string      $title,
        callable    $callback,
        string      $page,
        string      $section = 'default',
        array       $args = []
    );
    */

    add_settings_field(
        'data_source',
		esc_html__('Data Source', 'dailystoicquotes'),
        'dailystoicquotes_callback_field_radio',
        'dailystoicquotes',
        'dailystoicquotes_section',
		[ 'id' => 'data_source', 'label' => esc_html__('Where to pull the API data from', 'dailystoicquotes') ]
	);

}
add_action( 'admin_init', 'dailystoicquotes_register_settings' );