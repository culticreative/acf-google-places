<?php

/*
Plugin Name: ACF Places Biz
Plugin URI: https://github.com/culticreative/acf-places-biz
Description: Adds Google Places Autocomplete field that returns businesses (establishments) only
Version: 1.0.0
Author: Culti Creative
Author URI: https://github.com/culticreative
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined( 'ABSPATH' ))
    exit;

if (!class_exists( 'cc_acf_plugin_places_biz' )) :

    class cc_acf_plugin_places_biz {
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
            include_once(plugin_dir_path( __FILE__ ) . 'fields/class-cc-acf-field-places-biz.php');
            // new acf_places_field( $this->settings );
        }

    }

    new cc_acf_plugin_places_biz();

endif;

?>
