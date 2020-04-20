<!doctype html>
<html lang="en">

<head>
    <title><?php wp_title() ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php get_template_part('template-parts/navigation-menu'); ?>

    <div class="hero-wrap js-fullheight" style="background-image: url('<?php the_field('background', 'option') ?>');" data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
                <div class="col-md-12 ftco-animate">

                    <h2 class="subheading">
                        <?php
                        global $template;
                        if (
                            !(basename($template) === 'single-company.php') &&
                            !(basename($template) === 'page-contact.php') &&
                            !(basename($template) === 'page-team.php')
                        ) {
                            the_field('sup_title', 'option');
                        }
                        ?>
                    </h2>
                    <h1 class="mb-4 mb-md-0">
                        <?php
                        if (basename($template) === 'single-company.php') {
                            the_field('blog_title', 'option');
                        } else if (basename($template) === 'page-contact.php') {
                            the_field('contact_title', 'option');
                        } else if (basename($template) === 'page-team.php') {
                            the_field('about_title', 'option');
                        } else {
                            the_field('title', 'option');
                        };

                        ?></h1>
                    <p class="breadcrumbs show" style="display: none"><span class="mr-2"><a href="/">Home <i class="ion-ios-arrow-forward"></i></a></span> <span class="mr-2"><a href="/company">Blog <i class="ion-ios-arrow-forward"></i></a></span> <span>Blog Single <i class="ion-ios-arrow-forward"></i></span></p>
                    <p class="breadcrumbs show1 " style="display: none"><span class=" mr-2"><a href="/">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>About <i class="ion-ios-arrow-forward"></i></span></p>
                    <p class="breadcrumbs show2" style="display: none"><span class="mr-2"><a href="/">Home <i class="ion-ios-arrow-forward"></i></a></span> <span>About <i class="ion-ios-arrow-forward"></i></span></p>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="text">
                                <p><?php
                                    if (
                                        !(basename($template) === 'single-company.php') &&
                                        !(basename($template) === 'page-contact.php') &&
                                        !(basename($template) === 'page-team.php')
                                    ) {
                                        the_field('description', 'option');
                                    }
                                    ?></p>
                                <?php
                                if (
                                    !(basename($template) === 'single-company.php') &&
                                    !(basename($template) === 'page-contact.php') &&
                                    !(basename($template) === 'page-team.php')
                                ) {
                                    get_template_part('template-parts/arrow');
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>