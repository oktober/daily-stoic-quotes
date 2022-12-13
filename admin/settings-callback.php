<?php // Validation + Callback functions for the settings

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// validate plugin settings
function dailystoicquotes_validation_options( $input ) {

    // custom style
    $radio_options = array(
		'api'  => 'Use API',
		'json' => 'Use custom JSON file'
    );

    if ( ! isset( $input['data_source'] ) ) {
        $input['data_source'] = null;
    }
    if ( ! array_key_exists( $input['data_source'], $radio_options ) ) {
        $input['data_source'] = null;
    }

    return $input;
}

// callback: main section
function dailystoicquotes_callback_section() {
    echo '<p>' . esc_html__( 'These settings enable you to pick where you get the quote from.', 'dailystoicquotes' ) . '</p>';
}

// callback: radio field
function dailystoicquotes_callback_field_radio( $args ) {
    $options = get_option( 'dailystoicquotes_options', dailystoicquotes_options_default() );
    
    $id = isset( $args['id'] ) ? $args['id'] : '';
    $label = isset( $args['label'] ) ? $args['label'] : '';

    $selected_option = isset( $options[$id] ) ? sanitize_text_field( $options[$id] ) : '';

    $radio_options = array(
		'api'  => esc_html__( 'Use API', 'dailystoicquotes' ),
		'json' => esc_html__( 'Use custom JSON file', 'dailystoicquotes' )
    );

    foreach ( $radio_options as $value => $label ) {
        $checked = checked( $selected_option === $value, true, false );

        echo '<label><input name="dailystoicquotes_options[' . $id . ']" type="radio" value="' . $value . '"' . $checked . '> ';
        echo '<span>' . $label . '</span></label><br />';
    }
}