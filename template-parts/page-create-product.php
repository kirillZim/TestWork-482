<?php
/**
 * Template name: create product
 *
 */


get_header();
?>

<section id="page_create_product">
    <div class="wrapp">
        <h2><?php the_title() ?></h2>
        <?php if(is_user_logged_in()) : ?>
            <div class="woocommerce-message"> “name” removed.</div>
            <form action="/" id="create-product">
                <input type="text" name="post_title" class="field" placeholder="Product title">
                <input type="number" name="post_price" class="field" placeholder="Product price">
                <input type="date" name="post_date" class="field" placeholder="Date create">
                <select name="post_type" id="" class="field">
                    <option value="rare">Rare</option>
                    <option value="frequent">Frequent</option>
                    <option value="unusual">Unusual</option>
                </select>
                <?php echo $Upload_file->html_form_upload_files("post_upload_img") ?>
                <button>Create product</button>
            </form>
        <?php else : ?>
            <p class="aut_logout">Log in to create the product</p>
        <?php endif; ?>
    </div>
</section>

<?php

get_footer();