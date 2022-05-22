<?php
/**
 * Maneger Custom Columns
 *
 * @package  storefront
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Maneger_Custom_Columns' ) ) :
    class Maneger_Custom_Columns {

        public function __construct() {
            add_action( 'manage_product_posts_custom_column', array( $this, 'product_custom_column'), 5, 2 );
            add_action( 'manage_product_posts_columns', array( $this, 'remove_product_posts_columns'), 15, 2 );
        }

        public function remove_product_posts_columns( $columns ) {
            unset($columns['thumb']);
            $preview = array ( 'thumbnail' => '<span class="wc-image tips">image</span>', );
            $columns = array_slice( $columns, 0, 1, true ) + $preview + array_slice( $columns, 1, NULL, true );

            return $columns;
        }

        public function product_custom_column( $column, $post_id ) {
            switch ($column) {
                case 'thumbnail': 
                    $image = get_post_meta($post_id, '_image_custom_tabs', true);
                    if(!$image) {
                        $image = get_post_thumbnail_id($post_id);
                    } 
                    $thumbnail = wp_get_attachment_image( $image, array( 40, 40) );
                    echo '<a href="http://land.prolegh.ru/wp-admin/post.php?post='.$post_id.'&amp;action=edit">'.$thumbnail.'</a>';
                    break;
                default:
                    break;
            }
        }

        

    }
endif;

return new Maneger_Custom_Columns();