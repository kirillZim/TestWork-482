<?php
/**
 * Upload file
 *
 * @package  storefront
 * @since    2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Upload_file' ) ) :
    class Upload_file {

        public function __construct() {
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_script' ) );
            add_action( 'wp_ajax_ajax_fileload', array( $this, 'ajax_file_upload_callback' ) );
        }

        public function enqueue_style_script() {
            global $storefront_version;

            wp_enqueue_script( 'storefront-upload-files', get_template_directory_uri() . '/assets/js/upload-files.js', array(), $storefront_version, true );
            wp_enqueue_style( 'storefront-create-product', get_template_directory_uri() . '/assets/css/create-page.css', array(), $storefront_version );
            wp_localize_script('storefront-upload-files', 'upload_files' , array( 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce('uplfile') ));
        }

        public function html_form_upload_files( $name = "" ) {

            return '<div class="row-upload_files" id="image_upload_file">
                        <span>Image</span>
                        <span class="row-image upload"><span class="spinner"></span><img src="" alt=""></span>
                        <input type="file" multiple="multiple" accept="image/*">
                        <input type="hidden" name="'.$name.'" class="image_id" required>
                    </div>';
        }

        public function ajax_file_upload_callback(){
            check_ajax_referer( 'uplfile', 'nonce' );
        
            if( empty($_FILES) )
                wp_send_json_error( 'File no...' );
        
            $post_id = (int) $_POST['post_id'];
        
            $sizedata = getimagesize( $_FILES['upfile']['tmp_name'] );
            $max_size = 2000;
            if( $sizedata[0] > $max_size || $sizedata[1] > $max_size )
                wp_send_json_error( __('Error size') );
        
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
        
            add_filter( 'upload_mimes', function( $mimes ){
                return [
                    'jpg|jpeg|jpe' => 'image/jpeg',
                    'gif'          => 'image/gif',
                    'png'          => 'image/png',
                ];
            } );
        
            $uploaded_imgs = array();
        
            foreach( $_FILES as $file_id => $data ){
                $attach_id = media_handle_upload( $file_id, $post_id );
        
                if( is_wp_error( $attach_id ) )
                    $uploaded_imgs[] = 'Error `'. $data['name'] .'`: '. $attach_id->get_error_message();
                else
                    $uploaded_imgs[$attach_id] = wp_get_attachment_url( $attach_id );
            }
        
            wp_send_json_success( $uploaded_imgs );
        
        }

    }
endif;

$Upload_file = new Upload_file();

return $Upload_file;