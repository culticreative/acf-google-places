<?php

/*
Plugin Name: ACF Google Places Autocomplete
Plugin URI: https://github.com/maxjf1/acf-google-places
Description: Adds Google Places Autocomplete field
Version: 1.0.0
Author: Maxwell Souza
Author URI: https://github.com/maxjf1/
*/

if (!defined( 'ABSPATH' ))
    exit;

if (!class_exists( 'ACF_Places_Autocomplete' )) :

    class ACF_Places_Autocomplete {
        var $settings;

        function __construct() {

            $this->settings = array(
                'version'             => '1.0.0',
                'url'                 => plugin_dir_url( __FILE__ ),
                'path'                => plugin_dir_path( __FILE__ ),
                'enqueue_google_maps' => true,
            );

            add_action( 'acf/include_field_types', array($this, 'include_field') ); // v5
        }

        function include_field($version = false) {
            include_once(plugin_dir_path( __FILE__ ) . 'fields/acf-places-field.php');
            new acf_places_field( $this->settings );
        }

    }

    new ACF_Places_Autocomplete();

endif;

?>
