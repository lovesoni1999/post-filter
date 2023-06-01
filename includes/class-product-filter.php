<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check PRODUCT_FILTER class_exists or not.
 */
if ( ! class_exists( 'PRODUCT_FILTER' ) ) {

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
    class PRODUCT_FILTER {

        /**
         * The instance of this class.
         */

        private static $instance;

        public static function get_instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof PRODUCT_FILTER ) ) {
                self::$instance = new PRODUCT_FILTER;
                self::$instance->includes();
                self::$instance->setup_dependency();
            }

            return self::$instance;
        }

        /**
         * Define the core functionality of the plugin.
         */
        private function __construct() {
            self::$instance = $this;
        }

        private function includes() {

            /**
             * The class responsible for defining all functionality occur in the admin area.
             */
            require_once P_FILTER_PATH . '/admin/class-admin-product-filter.php';

            /**
             * The class responsible for defining all functionality occur in the public area.
             */
            require_once P_FILTER_PATH . '/public/class-public-product-filter.php';
        }

        /**
         * Setup actions.
         */
        public function setup_dependency() {

            /**
             * Setup dependency of PRODUCT_FILTER_ADMIN
             */
            PRODUCT_FILTER_ADMIN::get_instance();

            /**
             * Setup dependency of PRODUCT_FILTER_PUBLIC
             */
            PRODUCT_FILTER_PUBLIC::get_instance();
        }
    }
}
