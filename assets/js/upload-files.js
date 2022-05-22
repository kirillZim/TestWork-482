

jQuery("#image_upload_file .row-image").click( function() {
    jQuery(this).parent().find('input[type="file"]').click();
    return false;
})

jQuery('#image_upload_file').on('change', 'input[type="file"]', function() {
    if(jQuery(this).val() != '') {
        let direct = jQuery(this).parent();
        image_upload_file(direct)
        jQuery(this).parent().find('.row-image').removeClass("upload")
        return false;
    }
})


function image_upload_file(direct) {
    
    let ajaxurl = upload_files.url;
    let nonce   = upload_files.nonce;

    let file_input = jQuery(direct).find('input[type=file]');
    jQuery(direct).find('.spinner').addClass('is-active')
    
    files = file_input[0].files;

    if( typeof files == 'undefined' ) return;

    let data = new FormData();
    jQuery.each( files, function( key, value ){
        data.append( key, value );
    });

    data.append( 'action', 'ajax_fileload' );
    data.append( 'nonce', nonce );

    let reply = jQuery(direct).find('img');
    let repl = jQuery(direct).find('.image_id');

    // AJAX запрос
    jQuery.ajax({
        url         : ajaxurl,
        type        : 'POST',
        data        : data,
        cache       : false,
        dataType    : 'json',
        
        processData : false,
        
        contentType : false,
        
        success     : function( respond, status, jqXHR ){
            // ОК
            if( respond.success ){
                jQuery.each( respond.data, function( key, val ){
                    reply.attr( 'src', val );
                    repl.val( key );
                } );
                jQuery(direct).find('.spinner').removeClass('is-active')
            }
            // error
            else {
            }
        },
        // функция ошибки ответа сервера
        error: function( jqXHR, status, errorThrown ){
            jQueryreply.text( 'ОШИБКА AJAX запроса: ' + status );
        }

    });

};