<?php
/**
 * 
 *
 * @package  storefront
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action( 'post_submitbox_start', 'submitbox_misc_actions', 500 );
function submitbox_misc_actions( $post ) {
    if($post->post_type === "product") {
        ?>
            <span id="submitbox-reset-custom-tab">Reset custom tab</span>
            <div id="publishing-save-product-ajax">
                <button id="submitbox_misc_actions">Update</button>
                <span class="spinner"></span>
            </div>
        <?php
    }
}

add_action('save_post', 'save_my_post_type');
function save_my_post_type($post_id){
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['foo_doing_ajax']) && $_POST['foo_doing_ajax'] == true) {
        if('product' === $_POST['post_type']){
            wp_send_json_success();
            setcookie("wp-saving-post", $post_id.'-saved');
            exit;
        }
    }
}


add_action( 'admin_notices', 'sample_admin_notice__success');
function sample_admin_notice__success() { 
    ?>
    <div id="simple_admin_notice_custom" class="notice hidden notice-success is-dismissible">
        <p><?php _e( 'Done!', 'sample-text-domain' ); ?></p>
    </div>
    <?php
}
