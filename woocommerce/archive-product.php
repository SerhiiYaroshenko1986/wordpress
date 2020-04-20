<?php

/**
 * Template Name:Shop Page
 */
?>
<?php
defined('ABSPATH') || exit;
if (is_search()) {
    get_header();
?>
    <div class="container-fluid">
        <div class="row">
            <?php
            if (have_posts()) {
                while (have_posts()) : the_post();
            ?>
                    <div class=" col-lg-4 col-md-6 release-item">
                        <a class="mt-2" href="<?php the_permalink() ?>"> <?php woocommerce_template_loop_product_thumbnail() ?></a>
                        <a href="<?php the_permalink() ?>">
                            <p class="item-name"><?php the_title() ?></p>
                        </a>
                        <p class="item-color"><?php wc_insertAttributeColor() ?></p>
                        <p class="item-color"><?php wc_insertAttributeSize() ?></p>
                        <p class="item-price"><?php woocommerce_template_loop_price() ?></p>
                        <p class="shop-page-cart"><?php woocommerce_template_loop_add_to_cart(); ?></p>
                    </div>
            <?php endwhile;
            } else {
                echo __('No products found');
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
<?php
    get_footer();
} else {



    get_header();

?>
    <div class="container-fluid w-100">
        <div class="row">
            <div class="col-md-2 col-12 text-center">
                <h2>Categories</h2>
                <a id='0' class="categories-link">All</a><br />
                <?php
                $taxonomy     = 'product_cat';
                $orderby      = 'name';
                $show_count   = 0;      // 1 for yes, 0 for no
                $pad_counts   = 0;      // 1 for yes, 0 for no
                $hierarchical = 1;      // 1 for yes, 0 for no  
                $title        = '';
                $empty        = 1;

                $args = array(
                    'taxonomy'     => $taxonomy,
                    'orderby'      => $orderby,
                    'show_count'   => $show_count,
                    'pad_counts'   => $pad_counts,
                    'hierarchical' => $hierarchical,
                    'title_li'     => $title,
                    'hide_empty'   => $empty
                );
                $all_categories = get_categories($args);
                foreach ($all_categories as $cat) {
                    if ($cat->category_parent == 0) {
                        $category_id = $cat->term_id;
                        echo '<a id=' . $cat->name . ' ' . 'class="categories-link">' . $cat->name . '</a><br/>';
                    }
                }
                ?>
                <ul>
                    <?php listOfAttributes() ?>
                </ul>
            </div>
            <div class="col-md-10 ">

                <div class="row my-products">
                    <?php

                    if (have_posts()) {
                        while (have_posts()) : the_post();
                    ?>
                            <div class=" col-lg-4 col-md-6 release-item">
                                <a class="mt-2" href="<?php the_permalink() ?>"> <?php woocommerce_template_loop_product_thumbnail() ?></a>
                                <a href="<?php the_permalink() ?>">
                                    <p class="item-name"><?php the_title() ?></p>
                                </a>
                                <p class="item-color"><?php wc_insertAttributeColor() ?></p>
                                <p class="item-color"><?php wc_insertAttributeSize() ?></p>
                                <p class="item-price"><?php woocommerce_template_loop_price() ?></p>
                                <p class="shop-page-cart"><?php woocommerce_template_loop_add_to_cart(); ?></p>

                            </div>


                    <?php endwhile;
                    } else {
                        echo __('No products found');
                    }
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="pagination d-flex justify-content-center mb-3">
        <p> <?php
            printPagination();
            ?></p>
    </div>
<?php



    get_footer();
}
?>