<?php
/**
 * Add Create product
 *
 * @package  storefront
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Add_Create_Product' ) ) :
    class Add_Create_Product {

        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_script' ) );
            add_action( 'wp_ajax_create_product', array( $this, 'create_product' ) );
        }

        public function enqueue_style_script() {
            global $storefront_version;

            wp_enqueue_script( 'storefront-create-product', get_template_directory_uri() . '/assets/js/create-product.js', array(), $storefront_version, true );
            wp_enqueue_style( 'storefront-create-product', get_template_directory_uri() . '/assets/css/create-page.css', array(), $storefront_version );
            wp_localize_script('storefront-create-product', 'create_product' , array( 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce('create') ));
        }

        public function create_product() {
            check_ajax_referer( 'create', 'nonce' );

            $post_id = wp_insert_post(  wp_slash( array(
                'post_type'     => 'product',
                'post_status'   => 'publish',
                'post_title'    => sanitize_text_field( $_POST['post_title'] ),
            ) ) );

            update_post_meta($post_id, '_image_custom_tabs', $_POST['post_upload_img']);
            update_post_meta($post_id, '_regular_price', $_POST['post_price']);
            update_post_meta($post_id, '_price', $_POST['post_price']);
            update_post_meta($post_id, '_type_prodyct', $_POST['post_type']);
            update_post_meta($post_id, '_date_create', $_POST['post_date']);

            exit;
        }

    }
endif;

return new Add_Create_Product();