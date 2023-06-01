<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check PRODUCT_FILTER_PUBLIC class_exists or not.
 */
if ( ! class_exists( 'PRODUCT_FILTER_PUBLIC' ) ) {

    /**
     * The core plugin class.
     *
     * This is used to define internationalization, admin-specific hooks, and
     * public-facing site hooks.
     *
     * Also maintains the unique identifier of this plugin as well as the current
     * version of the plugin.
     *
     * @since      1.0.0
     */
    class PRODUCT_FILTER_PUBLIC {

        /**
         * The instance of this class.
         */

        private static $instance;

        public static function get_instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PRODUCT_FILTER_PUBLIC ) ) {
                self::$instance = new PRODUCT_FILTER_PUBLIC;
                self::$instance->setup_action();
            }

            return self::$instance;
        }

        /**
         * Define the core functionality of the plugin.
         */
        private function __construct() {
            self::$instance = $this;
        }

        /**
         * Setup actions.
         */
        public function setup_action() {

            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ]);
            add_shortcode('custom_product_filter', [ $this, 'custom_product_filter_cb'] );
            add_action( 'wp_ajax_filter_product_data', [ $this, 'custom_product_filter_cb' ]);
            add_action( 'wp_ajax_nopriv_filter_product_data', [ $this, 'custom_product_filter_cb' ]);
        }

        public function enqueue_scripts(){

            wp_enqueue_style('product-filter-style', P_FILTER_URL . 'public/assets/css/public-product-filter.css');
            wp_enqueue_script( 'product-filter-js', P_FILTER_URL . 'public/assets/js/public-product-filter.js', array( 'jquery', ), '', true );
            wp_localize_script(
                'product-filter-js',
                'p_filter_obj',
                array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                )
            );
        }

        public function custom_product_filter_cb( $atts ){

            $atts = shortcode_atts( array(
                'posts_per_page' => P_FILTER_PER_PAGE,
                'post_type' => 'product'
            ), $atts, 'custom_product_filter' );

            $args = [
                'post_type' => $atts['post_type'],
                'posts_per_page' => $atts['posts_per_page'],
                'order' => 'ASC',
                'ignore_sticky_posts' => true,
                'suppress_filters' => true
            ];
            $product_parent_terms = get_terms([
                'parent' => 0,
                'taxonomy'   => 'product-category',
                'hide_empty' => false,
            ]);

            $form_data_arr = array();

            if ( isset( $_POST['form_data'] ) ) {
                parse_str( $_POST['form_data'], $form_data_arr );
            }

            if ( ! isset($form_data_arr['all-product'])) {
                $filter_cat = ['relation' => 'OR'];
                if ( ! empty($form_data_arr['filter-product-cat'])) {
                    foreach ($form_data_arr['filter-product-cat'] as $filter_parent_cat) {
                        $filter_cat[] = [
                            'taxonomy' => 'product-category',
                            'terms' => $filter_parent_cat,
                        ];
                    }
                }
                if (!empty($filter_cat)) {
                    $args['tax_query'] = $filter_cat;
                }

            }

            if (defined('DOING_AJAX') && DOING_AJAX){

                if( isset( $_POST['product-filter-nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['product-filter-nonce'] ),'product-filter-action') ) {

                    ob_start();
                    echo $this->get_products_html($args);
                    $html = ob_get_contents();
                    ob_get_clean();

                    $response = [
                        'status' => true,
                        'html' => $html,
                        'error_message' => __('Please Fill Form', 'custom-product'),
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'error_message' => __('Something went wrong', 'custom-product'),
                    ];
                }

                wp_send_json($response);
            } else {
                ob_start();
                ?>
                <div class="product-filter-main-wrapper">
                    <div class="product-filter-area">
                        <div class="filter product-filter-wrap">
                            <form id="product-filter-form" method="post" name="product-filter-form">
                                <span><label><input type="checkbox" name="all-product"><?php _e('Show All', 'product-filter');?></label></span>
                                <?php
                                if( ! empty( $product_parent_terms ) ) {
                                    foreach ( $product_parent_terms as $parent_term ) {
                                        $product_terms = get_terms([
                                            'parent' => $parent_term->term_id,
                                            'taxonomy' => 'product-category',
                                            'hide_empty' => false,
                                        ]);
                                        ?>
                                        <span class="parent"><label><input type="checkbox" name="filter-product-cat[]" value="<?php echo $parent_term->term_id; ?>"><?php _e( $parent_term->name, 'product-filter');?></label></span><?php
                                        if( ! empty( $product_terms ) ) {
                                            foreach ( $product_terms as $term ) {
                                                ?><span class="child"><label><input type="checkbox" name="filter-product-cat[]" value="<?php echo $term->term_id; ?>"><?php _e( $term->name, 'product-filter');?></label></span><?php
                                            }
                                        }
                                    }
                                }
                                ?>
                                <input type="hidden" name="product-filter-nonce" class="product-filter-nonce" value="<?php echo wp_create_nonce('product-filter-action');?>">
                                <button type="button" name="filter-product" class="filter-product-btn"><?php _e('Filter', 'product-filter');?></button>
                            </form>
                        </div>
                    </div>
                    <div class="product-filter-content-wrap">
                        <?php
                       echo $this->get_products_html($args);
                       ?>
                   </div>
               </div>
            <?php
                return ob_get_clean();
            } ?>
            <?php
        }

        public function get_products_html( $args ){

            $query = new WP_Query( $args );
            ob_start();
            ?>
            <div class="products">
                <?php
                if ( $query->have_posts() ) {
                    foreach( $query->posts as $product ) {
                        $featured_url = ! empty( get_the_post_thumbnail_url( $product ) ) ? get_the_post_thumbnail_url( $product ) : P_FILTER_URL.'/public/assets/images/placeholder.png';
                        ?>
                        <div class="product">
                            <div class="product-img">
                                <img src="<?php echo $featured_url;?>" alt="productfeatured-url">
                            </div>
                            <h3><?php echo $product->post_title;?></h3>
                            <p><?php echo ! empty( $product->post_excerpt ) ? $product->post_excerpt : 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'?></p>
                            <div class="product-link">
                                <a href="<?php echo get_permalink( $product->ID );?>"><?php _e('Read More', 'product-filter');?></a>
                            </div>
                        </div>
                    <?php }
                }
                ?>
            </div>
            <?php
            return ob_get_clean();
        }
    }
}
