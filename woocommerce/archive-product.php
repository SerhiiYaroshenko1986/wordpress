 <?php

    /**
     * Template Name:Shop Page
     */
    ?>
 <?php
    defined('ABSPATH') || exit;
    get_header();

    ?>
 <div class="container-fluid w-100">
     <div class="row">
         <div class="col-md-2 col-12 text-left shop-categories">
             <h2 class="categories-filter-title"><?php the_field('categories_title', 214) ?></h2>
             <div class="categories-filter-wrap">
                 <div class="categories-filter-position">
                     <div>
                         <a id='0' class="categories-link-all"><? the_field('all_categories_name', 214) ?></a><br />
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
                                    $category_id = $cat->term_id; ?>
                                 <ul class="shop-categories-mainList">
                                     <a id='<?php echo  $cat->name ?> ' class="categories-link"><?php echo $cat->name ?></a>
                                     <?php
                                        foreach ($all_categories as $cat) {
                                        ?>
                                         <?php if ($cat->category_parent == $category_id) { ?>
                                             <li class="shop-categories-list-item"><a id='<?php echo $cat->name ?> ' class="categories-link"><?php echo $cat->name ?></a></li>
                                         <?php
                                            } ?>

                                     <?php
                                        } ?>
                                 </ul>

                         <?php
                                }
                            }
                            ?>
                     </div>
                     <div> <?php listOfAttributes() ?></div>
                 </div>
             </div>
         </div>
         <div class="col-md-10">
             <div class="row my-products">
                 <?php get_template_part('template-parts/shop-page/products-all') ?>
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
    ?>