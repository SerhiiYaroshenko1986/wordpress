<section class="new-releases-women container-fluid">
    <div class="row mt-2">
        <div class="release-heading col-md-6 p-0">
            <p class="release-heading-right-text"><?php the_field('mtitle') ?></p>
            <p class="release-heading-left-text"><?php the_field('msuptitle') ?></p>
        </div>
        <div class="release-heading-link col-md-6 p-0">
            <a href="/shop" class="all_link sm-link sm-link_padding-all sm-link5">
                <span class="sm-link__label"><?php the_field('mbutton') ?></span>
            </a>
        </div>
    </div>
    <div class="row">
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'product_cat' => 'mans',
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
    </div>
</section>