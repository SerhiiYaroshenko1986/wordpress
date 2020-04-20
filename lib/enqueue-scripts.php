<?php

/**
 * Enqueue all styles and scripts.
 * 
 * PHP version 7.2
 * 
 * @category  Theme
 * @package   Webpack-theme
 * @author    Oleg Draganchuk <oleg.draganchuk@gmail.com>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://pear.php.net/package/PackageName
 */

if (!function_exists('Wt_scripts')) :
   
    /**
     * Theme scripts
     *
     * @return void
     */
    function Wt_scripts()
    {
        // Enqueue the main Stylesheet.
        wp_enqueue_style(
            'main-stylesheet',
            get_template_directory_uri().'/dist/styles/main.css', 
            false,
            null,
            'all'
        );

        // Deregister the jquery version bundled with WordPress.
        wp_deregister_script('jquery');

        // CDN hosted jQuery placed in the header, as some plugins require that jQuery is loaded in the header.
        wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-2.2.4.min.js', array(), '2.2.4', false);
        wp_enqueue_script('jquery-ui', 'http://code.jquery.com/ui/1.12.1/jquery-ui.js', array(), '1.12.1', false);

        // Enqueue the main JS file.
        wp_enqueue_script('main-javascript', get_template_directory_uri().'/dist/scripts/main.js', array('jquery'), null, true);

        // Throw variables from back to front end.
        $themeVars = array(
            'home' => get_home_url(),
            'isHome' => is_front_page()
        );
        wp_localize_script('main-javascript', 'themeVars', $themeVars);

        // Comments reply script
        if (is_singular() && comments_open()) :
            wp_enqueue_script("comment-reply");
        endif;
    }

    add_action('wp_enqueue_scripts', 'Wt_scripts');
endif;