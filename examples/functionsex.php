<?php
// register scripts and styles
add_action('wp_enqueue_scripts', 'my_scripts_and_styles');

//register menus
add_action('after_setup_theme', 'register_menus');


//register widget zones
add_action('widgets_init', 'widgets_zones');

// change post lenght
add_filter('excerpt_length', function () {
    return 20;
});

add_filter('nav_menu_css_class', 'nav_class', 10, 2);
function nav_class($classes, $item)
{
    $classes[] = 'nav-item';
    return $classes;
}
// add className to link
function atg_menu_classes($classes, $item, $args)
{
    if ($args->theme_location == 'Header menu') {
        $classes[] = 'nav-link';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'atg_menu_classes', 1, 3);

function add_menuclass($ulclass)
{
    return preg_replace('/<a /', '<a class="nav-link"', $ulclass);
}
add_filter('wp_nav_menu', 'add_menuclass');


//   add fetared image to dashboard
function true_plugin_add_support()
{
    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'true_plugin_add_support');


function register_menus()
{
    register_nav_menus([
        'header_menu' => 'Header menu',
        'footer_menu' => 'Footer menu'
    ]);
}

function widgets_zones()
{
    register_sidebar(['name' => 'Sidebar 1', 'id' => 'sidebar-1']);
    register_sidebar(['name' => 'Sidebar 2', 'id' => 'sidebar-2']);
    register_sidebar(['name' => 'Sidebar 3', 'id' => 'sidebar-3']);
    register_sidebar(['name' => 'Sidebar 4', 'id' => 'sidebar-4']);
    register_sidebar(['name' => 'Sidebar 5', 'id' => 'sidebar-5']);
    register_sidebar(['name' => 'Sidebar 6', 'id' => 'sidebar-6']);
    register_sidebar(['name' => 'Sidebar 7', 'id' => 'sidebar-7']);
    register_sidebar(['name' => 'Sidebar 8', 'id' => 'sidebar-8']);
    register_sidebar(['name' => 'Sidebar 9', 'id' => 'sidebar-9']);
    register_sidebar(['name' => 'Sidebar 10', 'id' => 'sidebar-10']);
    register_sidebar(['name' => 'Sidebar 11', 'id' => 'sidebar-11']);
}


function my_scripts_and_styles()
{
    wp_enqueue_script('my-scripts', get_template_directory_uri() . '/js/jquery.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts1', get_template_directory_uri() . '/js/jquery-migrate-3.0.1.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts2', get_template_directory_uri() . '/js/popper.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts3', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts4', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts5', get_template_directory_uri() . '/js/jquery.waypoints.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts6', get_template_directory_uri() . '/js/jquery.stellar.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts7', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts8', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts9', get_template_directory_uri() . '/js/aos.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts10', get_template_directory_uri() . '/js/jquery.animateNumber.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts11', get_template_directory_uri() . '/js/scrollax.min.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts12', get_template_directory_uri() . '/js/google-map.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts13', get_template_directory_uri() . '/js/main.js', array(), '1.0.1', true);
    wp_enqueue_script('my-scripts14', get_template_directory_uri() . '/logIn/js/login.js', array(), '1.0.1', true);
    wp_localize_script('my-scripts14', 'myAjax', ['ajaxurl' => admin_url('admin-ajax.php')]);

    wp_enqueue_style('my-styles', get_template_directory_uri() . '/css/open-iconic-bootstrap.min.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles1', get_template_directory_uri() . '/css/animate.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles2', get_template_directory_uri() . '/css/owl.carousel.min.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles3', get_template_directory_uri() . '/css/owl.theme.default.min.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles4', get_template_directory_uri() . '/css/magnific-popup.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles5', get_template_directory_uri() . '/css/aos.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles6', get_template_directory_uri() . '/css/ionicons.min.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles7', get_template_directory_uri() . '/css/flaticon.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles8', get_template_directory_uri() . '/css/icomoon.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles9', get_template_directory_uri() . '/css/style.css', array(), '0.1.0', 'all');
    wp_enqueue_style('my-styles9', get_template_directory_uri() . '/css/main.css', array(), '0.1.0', 'all');
}


// login
add_action('wp_ajax_hello', 'registrenewUser', 100);
add_action('wp_ajax_nopriv_hello', 'registrenewUser', 100);
include('logIn/login.php');

// registry acf block
acf_register_block_type(array(
    'name'                => 'testimonial',
    'title'                => __('Testimonial'),
    'description'        => __('A custom testimonial block.'),
    'render_template'   => 'blocks/block-layout/testimonial.php',
    'category'            => 'common',
    'icon'                => 'admin-comments',
    'keywords'            => array('testimonial', 'quote'),

    // 'enqueue_style' => get_template_directory_uri() . '/css/style.css',


));
add_action('acf/init', 'acf_register_block_type');


// Calc time to read article

function calcTime()
{
    $post = get_post();
    $content = $post->post_content;
    $lenght = strlen($content);
    $time = $lenght / 120;
    echo round($time, 0);
}

// add custom fields
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'     => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'        => false
    ));

    acf_add_options_sub_page(array(
        'page_title'     => 'Theme Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'    => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'     => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'    => 'theme-general-settings',
    ));
}


// delate h2 tag from pagination
add_filter('navigation_markup_template', 'my_navigation_template', 10, 2);
function my_navigation_template($template, $class)
{
    return '
	<nav class="navigation %1$s" role="navigation">
		<div class="block-27">%3$s</div>
	</nav>';
}

function printPagination()
{
    $args = array(
        'show_all'     => true,
        'end_size'     => 1,
        'mid_size'     => 1,
        'prev_text'    => __('<'),
        'next_text'    => __('>'),
        'add_args'     => false,
        'add_fragment' => '',
        'screen_reader_text' => '',
        'type'         => 'array',
    );
    print_r(the_posts_pagination($args));
}

//  get taxeconomy list

function getTaxeconomy($taxonomy)
{
    $args = array('hide_empty=0');
    $terms = get_terms($taxonomy, $args);

    if (!empty($terms) && !is_wp_error($terms)) {
        $count = count($terms);
        $i = 0;
        $term_list = '<p class="my_term-archive">';
        foreach ($terms as $term) {
            $i++;
            $term_list .= '<a href="' . get_term_link($term) . '" title="' . sprintf(__('View all post filed under %s', 'my_localization_domain'), $term->name) . '">' . $term->name . '</a>';
            if ($count != $i) {
                $term_list .= ' &middot; ';
            } else {
                $term_list .= '</p>';
            }
        }
        echo $term_list;
    }
}

// Custom Excerpt function for Advanced Custom Fields
function custom_field_excerpt()
{
    global $post;
    $text = get_field('description');
    if ('' != $text) {
        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]>', $text);
        $excerpt_length = 20;
        $excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
        $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
    }
    return apply_filters('the_excerpt', $text);
}
