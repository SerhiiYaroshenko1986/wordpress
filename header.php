<?php

/**
 * Header template part
 * 
 * PHP version 7.2
 * 
 * @category  CategoryName
 * @package   PackageName
 * @author    Original Author <author@example.com>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://pear.php.net/package/PackageName
 */

?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700|Roboto:400,400i,700,700i" rel="stylesheet" />

  <?php wp_head(); ?>
  <title>Gymshark</title>
</head>

<body>
  <header class="header pt-2 container-fluid">
    <div class="row">
      <div class="col-md-2">
        <a href="/">
          <svg style="width: 60px; height: 60px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30.4 23.2" enable-background="new 0 0 30.4 23.2" xml:space="preserve">
            <path fill="#010101" d="M0,0h30.4c0,0-14,15.5-21,23.2c0.3-0.6,0.5-1.1,0.9-1.6c-0.3,0-0.7-1.3-1-1.9l-1.2,0.6
	c-0.2,0.2-0.6,0.1-0.8-0.1c0-0.1-0.1-0.1-0.1-0.2c-0.2-0.4-0.3-0.8-0.5-1.3c-0.5,0.3-1,0.5-1.5,0.7l-4.9-2.1h3.5l1.6,1.3
	c0.4-0.4,0.7-1,1-1.3h0.9c0.3,0.3,0.6,0.9,1,1.3c0.4-0.4,0.8-0.9,1.2-1.2h0.6c0.3,0.3,0.7,0.8,1,1.1l0.1,0c0,0,0.6-0.8,0.9-1.1h0.7
	c0,0,9-13.5,9-13.6C14.6,2.5,0,0,0,0" />
            <polygon fill="#010101" points="8.9,8 3.9,5.2 12.8,7.3 " />
          </svg>
        </a>
      </div>
      <div class="col-md-5 pt-3">
        <div class="nav nav-pills d-flex justify-content-center align-items-center" id="ftco-nav">
          <?php
          $args = array(
            'container' => false,
            'items_wrap'      => '<ul id="%1$s" class="header-menu ">%3$s</ul>',
            'theme_location' => 'header_menu',

          );
          ?>
          <?php wp_nav_menu($args); ?>
        </div>
        </nav>
      </div>
      <div class="col-md-5 text-md-right pt-2 header-options">
        <div><?php get_product_search_form() ?></div>
        <div>
          <a class="site-header__topnav__item site-header__topnav__item--account" href="/account/login">
            <svg style="width: 25px; height: 25px;" aria-hidden="true" focusable="false" role="presentation" class="icon icon-login" viewBox="0 0 100 100">
              <path d="M85.355 64.645c-5.445-5.446-11.927-9.478-19-11.918 7.575-5.217 12.551-13.948 12.551-23.82C78.906 12.966 65.94 0 50 0 34.061 0 21.094 12.967 21.094 28.906c0 9.873 4.976 18.604 12.55 23.821-7.071 2.44-13.553 6.472-19 11.918C5.202 74.088 0 86.645 0 100h7.813c0-23.262 18.925-42.188 42.187-42.188 23.262 0 42.188 18.926 42.188 42.188H100c0-13.355-5.201-25.912-14.645-35.355zM50 50c-11.631 0-21.094-9.462-21.094-21.094C28.906 17.275 38.37 7.813 50 7.813c11.631 0 21.094 9.462 21.094 21.093C71.094 40.538 61.63 50 50 50z" fill="#000" fill-rule="nonzero" />
            </svg>
          </a>
        </div>
        <div class="header-cart">
          <a class="header-cart-link" href="/cart" id="open-cart">
            <span class="header-cart-count"><?php echo WC()->cart->get_cart_contents_count() ?></span>
            <svg style="width: 30px; height: 30px;" aria-hidden="true" focusable="false" role="presentation" class="header-cart-icon" viewBox="0 0 100 100">
              <path d="M85 24.789H68.993v-5.66C68.993 8.581 60.474 0 50.003 0c-10.47 0-18.988 8.581-18.988 19.13v5.659H15V100h70V24.789zm-45-5.407C40 12.841 44.423 10 49.937 10 55.452 10 60 12.84 60 19.382V25H40v-5.618z" fill="#000" fill-rule="nonzero" />
            </svg>
          </a>
        </div>

      </div>
    </div>
  </header>