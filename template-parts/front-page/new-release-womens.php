<section class="new-releases-women container-fluid">
    <div class="row mt-2">
        <div class="release-heading col-md-6 p-0">
            <p class="release-heading-right-text">WOMEN</p>
            <p class="release-heading-left-text">NEW RELEASES</p>
        </div>
        <div class="release-heading-link col-md-6 p-0">
            <a href="" class="all_link sm-link sm-link_padding-all sm-link5">
                <span class="sm-link__label">View All</span>
            </a>
        </div>
    </div>
    <div class="row">
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'product_cat' => 'womens',
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $loop = new WP_Query($args);
        if ($loop->have_posts()) {
            while ($loop->have_posts()) : $loop->the_post();
        ?>
                <div class="col-lg-3 col-md-6 release-item">
                    <a href="<?php the_permalink() ?>"> <?php woocommerce_template_loop_product_thumbnail() ?></a>
                    <a href="<?php the_permalink() ?>">
                        <p class="item-name"><?php the_title() ?></p>
                    </a>
                    <p class="item-color">Black</p>
                    <p class="item-price"><?php woocommerce_template_loop_price() ?></p>
                    <p class="shop-page-cart"><?php woocommerce_template_loop_add_to_cart();

                                                ?></p>
                </div>


        <?php endwhile;
        } else {
            echo __('No products found');
        }
        wp_reset_postdata();
        ?>
    </div>
</section>