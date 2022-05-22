

    jQuery('#my_custom_product_data').on('click', '.upload_image_button', function(){
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = jQuery(this);
        wp.media.editor.send.attachment = function(props, attachment) {
            jQuery(button).parent().prev().attr('src', attachment.url);
            jQuery(button).prev().val(attachment.id);
            wp.media.editor.send.attachment = send_attachment_bkp;
        }
        wp.media.editor.open(button);
        return false;    
    });

    function remove_image() {
        var src = jQuery('.remove_image_button').parents(".image").find("img").attr('data-src');
        jQuery('.remove_image_button').parents(".image").find("img").attr('src', src);
        jQuery('.remove_image_button').parent().find("input").val('');
    }
    

    jQuery('#my_custom_product_data').on('click', '.remove_image_button', function(){
        var r = confirm("Delete?");
        if (r == true) {
            remove_image()
        }
        return false;
    });


    function save_post_admin(form, notice) {
        jQuery('#submitbox_misc_actions').parent().find(".spinner").addClass('is-active')

        jQuery(window).unbind('beforeunload.edit-post');
        jQuery(window).on( 'beforeunload.edit-post', function() {
                var editor = typeof tinymce !== 'undefined' && tinymce.get('content');

                if ( ( editor && !editor.isHidden() && editor.isDirty() ) ||
                        ( wp.autosave && wp.autosave.getCompareString() != ajax_updated) ) { 
                        return postL10n.saveAlert;
                }   
        });

        jQuery.ajax({
            url: post_sale.url,
            type: "POST",
            data: form,
            dataType: "json",
            success:function(data) {
                if (typeof tinyMCE !== 'undefined') {
                    for (id in tinyMCE.editors) {
                        if (tinyMCE.get(id))
                            tinyMCE.get(id).isNotDirty = true
                            console.log(tinyMCE.get(id))
                    }
                }
                jQuery('#simple_admin_notice_custom').removeClass('hidden')
                jQuery('#simple_admin_notice_custom p').text(notice)
                jQuery('#submitbox_misc_actions').parent().find(".spinner").removeClass('is-active')
            },
            error: function(data){
            }
        })
    }

    jQuery('#submitbox_misc_actions').click(function() {
        window.tinyMCE.triggerSave()
        let form = jQuery('form#post').serializeArray()
        form[form.length] = {name: "foo_doing_ajax", value: true}

        save_post_admin(form, "Product updated.")

        return false;
    })

    jQuery("#submitbox-reset-custom-tab").click(function() {
        remove_image()

        jQuery("#my_custom_product_data #_type_prodyct").val("")
        jQuery("#my_custom_product_data #_date_create").val("")
        
        let form = jQuery('form#post').serializeArray()
        form[form.length] = {name: "foo_doing_ajax", value: true}

        save_post_admin(form, "Reset custom tab.")
    })