<?php
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC'
);
$loop = new WP_Query($args);
if ($loop->have_posts()) {
    while ($loop->have_posts()) : $loop->the_post();
?>
                <?php
                get_template_part('template-parts/front-page/templateForProduct')
                ?>


        <?php endwhile;
} else {
    echo __('No products found');
}
wp_reset_postdata();
        ?>