<div class="col-lg-3 col-md-6 release-item">
    <a href="<?php the_permalink() ?>"> <?php woocommerce_template_loop_product_thumbnail() ?></a>
    <a href="<?php the_permalink() ?>">
        <p class="item-name"><?php the_title() ?></p>
    </a>
    <p class="item-color"><?php wc_insertAttributeColor() ?></p>
    <p class="item-color"><?php wc_insertAttributeSize() ?></p>
    <p class="item-price"><?php woocommerce_template_loop_price() ?></p>
</div>