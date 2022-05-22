

jQuery("#create-product").submit(function() {
    let form = jQuery(this).serializeArray()
    form[form.length] = {name: "action", value: "create_product"}
    form[form.length] = {name: "nonce", value: create_product.nonce}

    jQuery.ajax({
        url: create_product.url,
        type: "POST",
        data: form,
        success:function() {
            jQuery("#page_create_product .woocommerce-message").fadeIn()
            jQuery("#page_create_product .woocommerce-message").text("New product")
        
            jQuery("#create-product input").val("")
            jQuery("#image_upload_file .row-image img").attr("src", "")
            jQuery("#image_upload_file .row-image").addClass("upload")
        }
    })

    return false;
})