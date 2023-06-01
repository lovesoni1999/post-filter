<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 */

if( !class_exists('P_FILTER_Activator')) {

    class P_FILTER_Activator{

        public static function activate() {
            $new_page = array('post_title'    => 'Product Filter Page',
                'post_content'  => '<!-- wp:columns {"align":"wide"} --><div class="wp-block-columns alignwide"><!-- wp:column --><div class="wp-block-column"><!-- wp:shortcode -->[custom_product_filter]<!-- /wp:shortcode --></div><!-- /wp:column --></div><!-- /wp:columns -->',
                'post_status'   => 'publish',
                'post_type'     => 'page'
            );
            $post_id = wp_insert_post( $new_page );
        }
    }
}