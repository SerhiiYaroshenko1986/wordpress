<?php get_header(); ?>

<div class="text-center hero " style="background: url(<?php the_field('background_image') ?>) no-repeat;">
    <div class="container d-flex flex-column justify-content-center  align-items-start h-100 hero-block">
        <h1 class="hero-title"><?php the_field('title') ?></h1>
        <p class="hero-subtitle"><?php the_field('suptitle') ?></p>
        <button class="hero-btn" onclick="location.href='/shop'" type="button"><?php the_field('button') ?></button>
    </div>
</div>

<?php get_template_part('template-parts/front-page/new-release-womens') ?>

<?php get_template_part('template-parts/front-page/new-release-mans') ?>










<?php get_footer(); ?>