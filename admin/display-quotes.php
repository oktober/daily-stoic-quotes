<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// function to grab the quote and store in transient, if expired
function dailystoicquotes_select_quote() {
    // default the to use the API
    $data_source = 'api';

    // get the option to set the data_source
    $options = get_option( 'dailystoicquotes_options', dailystoicquotes_options_default() );

    if ( isset( $options['data_source'] ) && ! empty( $options['data_source'] ) ) {
        $data_source = sanitize_text_field( $options['data_source'] );
    }

    // get quote from transient -> will return false if it doesn't exist or is expired
    $quote = get_transient( 'daily_stoic_quotes' );

    // if transient doesn't exist/is expired, get the quote and set the transient
    if ( false === $quote ) {

        // check if we're using the API (default)
        if ( 'api' === $data_source ) {

            // get the quote from the Stoic Quotes API
            $response = wp_safe_remote_get( 'https://stoicquotesapi.com/v1/api/quotes/random' );
            $single_quote = json_decode( wp_remote_retrieve_body( $response ) );
    
            $quote = new stdClass();
            $quote->body = $single_quote->body;
            $quote->author = $single_quote->author;

        } else {

            // get the quote from the JSON file
            $all_quotes = json_decode( file_get_contents( DAILYSTOICQUOTES_PLUGIN_DIR . 'includes/' . 'quotes.json' ) );

            // find a random number between 0 and the number of quotes
            // to select a random quote from the JSON file
            $random_value = mt_rand( 0, count( $all_quotes ) - 1 );
    
            $quote = new stdClass();
            $quote->body = $all_quotes[ $random_value ]->text;
            $quote->author = $all_quotes[ $random_value ]->author;
        }

        // set transient to cache results for 12 hours
        set_transient( 'daily_stoic_quotes', $quote, 12 * HOUR_IN_SECONDS );

    }

    return esc_html( '"' . $quote->body . '" - ' . $quote->author );

}

// display the quote as an admin notice-info
function dailystoicquotes_display_stoic_quotes() {
    $chosen = dailystoicquotes_select_quote();
    $lang   = '';
    if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
        $lang = ' lang="en"';
    }

    printf(
        '<div class="notice notice-info"><p id="stoic"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p></div>',
        esc_html__( 'Quote from stoic philosopher:', 'dailystoicquotes' ),
        $lang,
        $chosen
    );
}
// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'dailystoicquotes_display_stoic_quotes' );