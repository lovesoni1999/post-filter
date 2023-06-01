<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check PRODUCT_FILTER_ADMIN class_exists or not.
 */
if ( ! class_exists( 'PRODUCT_FILTER_ADMIN' ) ) {

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
    class PRODUCT_FILTER_ADMIN {

        /**
         * The instance of this class.
         */

        private static $instance;

        public static function get_instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PRODUCT_FILTER_ADMIN ) ) {
                self::$instance = new PRODUCT_FILTER_ADMIN;
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
            add_action( 'init', [ $this, 'init_product_cpt' ] );
        }

        public function init_product_cpt() {
            $labels = array(
                'name'                  => _x( 'Products', 'Post type general name', 'product-filter' ),
                'singular_name'         => _x( 'Product', 'Post type singular name', 'product-filter' ),
                'menu_name'             => _x( 'Products', 'Admin Menu text', 'product-filter' ),
                'name_admin_bar'        => _x( 'Product', 'Add New on Toolbar', 'product-filter' ),
                'add_new'               => __( 'Add New', 'product-filter' ),
                'add_new_item'          => __( 'Add New Product', 'product-filter' ),
                'new_item'              => __( 'New Product', 'product-filter' ),
                'edit_item'             => __( 'Edit Product', 'product-filter' ),
                'view_item'             => __( 'View Product', 'product-filter' ),
                'all_items'             => __( 'All Products', 'product-filter' ),
                'search_items'          => __( 'Search Products', 'product-filter' ),
                'not_found'             => __( 'No Products found.', 'product-filter' ),
                'not_found_in_trash'    => __( 'No Products found in Trash.', 'product-filter' ),

            );
            $args = array(
                'labels'             => $labels,
                'description'        => 'Product custom post type.',
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'product' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 20,
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
                'show_in_rest'       => true,
                'menu_icon'          => 'dashicons-products'
            );

            register_post_type( 'product', $args );

            unset( $labels );
            unset( $args );

            $labels = array(
                'name'              => _x( 'Product Category', 'taxonomy general name', 'product-filter' ),
                'singular_name'     => _x( 'Product Category', 'taxonomy singular name', 'product-filter' ),
                'search_items'      => __( 'Search Product Category', 'product-filter' ),
                'all_items'         => __( 'All Product Category', 'product-filter' ),
                'parent_item'       => __( 'Parent Product Category', 'product-filter' ),
                'parent_item_colon' => __( 'Parent Product Category:', 'product-filter' ),
                'edit_item'         => __( 'Edit Product Category', 'product-filter' ),
                'update_item'       => __( 'Update Product Category', 'product-filter' ),
                'add_new_item'      => __( 'Add New Product Category', 'product-filter' ),
                'new_item_name'     => __( 'New Product Category Name', 'product-filter' ),
                'menu_name'         => __( 'Product Category', 'product-filter' ),
            );

            $args = array(
                'hierarchical'      => true,
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'product-category' ),
            );

            register_taxonomy( 'product-category', array( 'product' ), $args );
        }
    }
}
