<?php
/**
 * Product Custom Tab
 *
 * @package  storefront
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Product_Custom_Tabs' ) ) :
    class Product_Custom_Tabs {

        public function __construct() {
            add_action( 'woocommerce_product_data_tabs', array( $this, 'custom_product_data_tab' ) );
            add_action('woocommerce_product_data_panels', array( $this, 'custom_tab_data_fields' ) );
            add_action('woocommerce_process_product_meta_simple', array( $this, 'custom_tab_update' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style_script' ) );
            
        }


        public function enqueue_style_script() {
            global $storefront_version;

            wp_enqueue_script( 'storefront-admin-product', get_template_directory_uri() . '/assets/js/admin/admin-product.js', array(), $storefront_version, true );
            wp_enqueue_style( 'storefront-admin-product', get_template_directory_uri() . '/assets/css/admin/admin-product.css', array(), $storefront_version );
            wp_localize_script('storefront-admin-product', 'post_sale' , array( 'url' => admin_url( 'post.php' ) ));
        }


        public function custom_product_data_tab( $product_data_tabs ) {
            $product_data_tabs['my-custom-tab'] = array(
                'label' => __( 'Custom Tab', 'woocommerce' ),
                'target' => 'my_custom_product_data',
                'class'     => array( 'show_if_simple' ),
            );
            return $product_data_tabs;
        }

        public function custom_tab_data_fields() {
            $post_id = $_GET['post'];
            $image = get_post_meta($post_id, '_image_custom_tabs', true);

            ?>
            <div id='my_custom_product_data' class='panel woocommerce_options_panel'>
                <p class="form-field">
                    <label for=""><?php esc_html_e( 'Image', 'storefront' ); ?></label>
                    <?php $this->image_uploader_field("_image_custom_tabs", $image) ?>
                </p>
                <?php 
                    woocommerce_wp_select(
                        array(
                            'id' => '_type_prodyct',
                            'label' => __( 'Type product', 'storefront' ),
                            'options' => array(
                                'rare' => __( 'Rare', 'storefront' ),
                                'frequent' => __( 'Frequent', 'storefront' ),
                                'unusual' => __( 'Unusual', 'storefront' )
                            )
                        )
                    ); 
                    woocommerce_wp_text_input(
                        array(
                            'id' => '_date_create',
                            'label' => __( 'Date create', 'storefront' ),
                            'type' => 'date',
                        )
                    ); 
                ?>

            </div>
            <?php
        }

        public function custom_tab_update($post_id) {
            $image = $_POST['_image_custom_tabs'];
            $type = $_POST['_type_prodyct'];
            $date = $_POST['_date_create'];


            update_post_meta($post_id, '_image_custom_tabs', $image);
            update_post_meta($post_id, '_type_prodyct', $type);
            update_post_meta($post_id, '_date_create', $date);
        }

        public function image_uploader_field( $name, $value = '', $w = 115, $h = 90) {
            $default = get_avatar_url( '1', $args = null );
            if( $value ) {
                $image_attributes = wp_get_attachment_image_src( $value, array($w, $h) );
                $src = $image_attributes[0];
            } else {
                $src = $default;
            }
            echo '
            <span class="image">
                <img data-src="' . $default . '" src="' . $src . '" width="' . $w . 'px" height="' . $h . 'px" />
                <span>
                    <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
                    <button type="submit" form="update_img" class="upload_image_button button">Set image</button>
                    <button type="submit" class="remove_image_button button">×</button>
                </span>
            </span>
            ';
        }
    }
endif;

return new Product_Custom_Tabs();