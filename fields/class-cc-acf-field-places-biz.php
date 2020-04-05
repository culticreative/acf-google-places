<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('cc_acf_field_places_biz') ) :


class cc_acf_field_places_biz extends acf_field {
    protected $settings;

    function __construct($settings) {

        $this->name = 'places_biz';
        $this->label = __( "Places Biz", 'cc' );
        $this->category = 'jquery';
        $this->defaults = array(
            'center_lat' => '',
            'center_lng' => ''
        );

        $this->settings = $settings;
        // do not delete!
        parent::__construct();
    }

    function input_admin_enqueue_scripts() {

        // localize
        acf_localize_text( array(
            'Sorry, this browser does not support geolocation' => __( 'Sorry, this browser does not support geolocation', 'acf' ),
        ) );

        if (!acf_get_setting( 'enqueue_google_maps' )) {
            return;
        }

        $url = $this->settings['url'];
        $version = $this->settings['version'];

        wp_register_script( 'cc', "{$url}assets/js/input.js", $version );
        wp_enqueue_script( 'cc' );

        $api = array(
            'key'       => acf_get_setting( 'google_api_key' ),
            'client'    => acf_get_setting( 'google_api_client' ),
            'libraries' => 'places',
            'ver'       => 3,
            'callback'  => 'document.autocompleteField'
        );

        $api = apply_filters( 'acf/fields/google_map/api', $api );

        if (empty( $api['key'] ))
            unset( $api['key'] );
        if (empty( $api['client'] ))
            unset( $api['client'] );

        $api_path = add_query_arg( $api, 'https://maps.googleapis.com/maps/api/js' );

        wp_register_script( 'google_maps', $api_path, false, '3' );
        wp_enqueue_script( 'google_maps' );

    }

    function render_field($field) {
        // validate value
        if (empty( $field['value'] )) {
            $field['value'] = array();
        }

        $field['value'] = wp_parse_args( $field['value'], array(
            'address' => '',
            'formatted_address' => '',
        ) );

        $atts = array(
            'id'       => $field['id'],
            'class'    => "acf-google-map {$field['class']}",
            'data-lat' => $field['center_lat'],
            'data-lng' => $field['center_lng'],
        );

        if ($field['value']['address']) {
            $atts['class'] .= ' -value';
        }

        ?>
        <div <?php echo acf_esc_attrs( $atts ); ?>>
            <div class="acf-hidden">
                <?php acf_hidden_input( array('name' => $field['name'], 'value' => $field['value']['address'] ? $field['value'] : null) ); ?>
            </div>
            <div class="title" style="border:none">
                <input
                        data-maps-places_biz
                        class="search"
                        type="text"
                        placeholder="<?php _e( "Search for business...", 'cc' ); ?>"
                        value="<?php echo esc_attr( $field['value']['formatted_address'] ); ?>"
                />
            </div>

        </div>
        <?php

    }

    /*function validate_value($valid, $value, $field, $input) {
        if ($field['required'] && empty( $value )) {
            return false;
        }

        return $valid;
    }*/

    function update_value($value, $post_id, $field) {
        // decode JSON string.
        if (is_string( $value )) {
            $value = json_decode( wp_unslash( $value ), true );
        }

        // Ensure value is an array.
        if ($value) {
            return (array)$value;
        }

        // Return default.
        return false;
    }

    function render_field_settings($field) {

        // center_lat
        acf_render_field_setting( $field, array(
            'label'        => __( 'Center', 'cc' ),
            'instructions' => __( 'Center the initial map', 'cc' ),
            'type'         => 'text',
            'name'         => 'center_lat',
            'prepend'      => 'lat',
            'placeholder'  => '-37.81411'
        ) );


        // center_lng
        acf_render_field_setting( $field, array(
            'label'        => __( 'Center', 'cc' ),
            'instructions' => __( 'Center the initial map', 'cc' ),
            'type'         => 'text',
            'name'         => 'center_lng',
            'prepend'      => 'lng',
            'placeholder'  => '144.96328',
            '_append'      => 'center_lat'
        ) );

    }
}

// initialize
new cc_acf_field_places_biz( $this->settings );


// class_exists check
endif;

?>
