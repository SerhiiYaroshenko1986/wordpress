<?php

/**
 * Theme functions and definitions.
 * 
 * PHP version 7.2
 * 
 * @category  ThemeFunctions
 * @package   Webpack-theme
 * @author    Oleg Draganchuk <oleg.draganchuk@gmail.com>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://pear.php.net/package/PackageName
 */

/**
 * Text domain definition
 */

defined('THEME_TD') ? THEME_TD : define('THEME_TD', 'our-way-tours');

// Load modules

$theme_includes = [
    '/lib/enqueue-scripts.php', // Enqueue styles and scripts
];
foreach ($theme_includes as $file) {
    if (!$filepath = locate_template($file)) {
        continue;
        trigger_error(
            sprintf(__('Error locating %s for inclusion', THEME_TD), $file),
            E_USER_ERROR
        );
    }
    include_once $filepath;
}
unset($file, $filepath);


/**
 * Theme the TinyMCE editor (Copy post/page text styles in this file)
 */
add_editor_style('assets/dist/css/custom-editor-style.css');

/**
 * Custom CSS for the login page
 *
 * @return void
 */
function loginCSS()
{
    echo '<link 
        rel="stylesheet" 
        type="text/css" 
        href="'
        . get_template_directory_uri(THEME_TD) . 'assets/dist/css/wp-login.css"/>';
}
add_action('login_head', 'loginCSS');


// registry menus
function register_menus()
{
    register_nav_menus([
        'header_menu' => 'Header menu',
        'footer_menu' => 'Footer menu'
    ]);
}

add_action('after_setup_theme', 'register_menus');


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
    return preg_replace('/<a /', '<a class="header-link-text"', $ulclass);
}
add_filter('wp_nav_menu', 'add_menuclass');


// declarate woocommerce support
function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

// Change thumbnail size 
if (function_exists('add_image_size')) {
    add_image_size('custom-thumb', 90, 125);
}
add_action('wp_footer', 'bbloomer_cart_refresh_update_qty');

// update product quantity on cart page
function bbloomer_cart_refresh_update_qty()
{
    if (is_cart()) {
?>
        <script type="text/javascript">
            var timeout;
            jQuery(function($) {
                $('.woocommerce-cart-form').on('change', 'input.qty', function() {
                    if (timeout !== undefined) {
                        clearTimeout(timeout);
                    }
                    timeout = setTimeout(function() {
                        $("[name='update_cart']").trigger("click");
                    }, 1000); // 1 second delay, half a second (500) seems comfortable too
                });
            });
        </script>
        <?php
    }
}

// extend file types to load 
function my_myme_types($mime_types)
{
    $mime_types['svg'] = 'image/svg+xml'; //Adding svg extension
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);
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
    acf_add_options_sub_page(array(
        'page_title'     => 'Theme Subscribe Settings',
        'menu_title'    => 'Subscribe',
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


// Sorting by attributes (color)
function import_attributes_color()
{
    $taxonomy = $_REQUEST['className'];
    $terms = $_REQUEST['id'];
    // The query
    $the_query = new WP_Query(array(
        'post_type'      => array('product'),
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'tax_query'      => array(array(
            'taxonomy'        => $taxonomy,
            'field'           => 'slug',
            'terms'           =>  array($terms),
            'operator'        => 'IN',
        ))
    ));

    // The Loop

    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post(); ?>
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
        <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo 'No posts found';
    endif;

    die;
}


add_action('wp_ajax_fetch_attributes_color', 'import_attributes_color');
add_action('wp_ajax_nopriv_fetch_attributes_color', 'import_attributes_color');

// Sorting by attributes (size)
function import_attributes_size()
{
    $taxonomy = $_REQUEST['className'];
    $terms = $_REQUEST['id'];
    // The query
    $the_query = new WP_Query(array(
        'post_type'      => array('product'),
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'tax_query'      => array(array(
            'taxonomy'        => $taxonomy,
            'field'           => 'slug',
            'terms'           =>  array($terms),
            'operator'        => 'IN',
        ))
    ));

    // The Loop

    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post(); ?>
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
    <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo 'No posts found';
    endif;

    die;
}


add_action('wp_ajax_fetch_attributes_size', 'import_attributes_size');
add_action('wp_ajax_nopriv_fetch_attributes_size', 'import_attributes_size');

// Sorting by categories
function import_post()
{
    $category = $_REQUEST['fetch'];
    $category = strtolower($category);
    $query_args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'product_cat' => $category,
    );
    $the_query = new WP_Query($query_args);
    ?>

    <?php
    if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <div class=" col-lg-4 col-md-6 release-item">
                <a class="mt-2" href="<?php the_permalink() ?>"> <?php woocommerce_template_loop_product_thumbnail() ?></a>
                <a href="<?php the_permalink() ?>">
                    <p class="item-name"><?php the_title() ?></p>
                </a>
                <p class="item-color"><?php wc_insertAttributeColor() ?></p>
                <p class="item-color"><?php wc_insertAttributeSize() ?></p>
                <p class="item-price"><?php woocommerce_template_loop_price() ?></p>
                <p class="shop-page-cart"><?php woocommerce_template_loop_add_to_cart();

                                            ?></p>
            </div>
        <?php
        endwhile;
        ?>
    <?php
        wp_reset_postdata();
    else :
        echo 'No posts found';
    endif;

    die;
}
add_action('wp_ajax_fetch_posts', 'import_post');
add_action('wp_ajax_nopriv_fetch_posts', 'import_post');

// add attributes
add_action('woocommerce_shop_loop_item_title', 'wc_insertAttributeColor , wc_insertAttributeColorSingleProduct', 15);
add_action('woocommerce_shop_loop_item_title', 'wc_insertAttributeSize, wc_insertAttributeSizeSingleProduct', 15);
function wc_insertAttributeColor()
{
    global $product;
    $color = $product->get_attribute('color');
    echo __($color, 'woocommerce');
}

function wc_insertAttributeSize()
{
    global $product;
    $size = $product->get_attribute('size');
    echo __($size, 'woocommerce');
}

function wc_insertAttributeColorSingleProduct()
{
    global $product;
    $productId = $product->get_id();
    $colors = $product->get_attribute('color');
    $colorsArr = explode(",", $colors);
    $firstColor = $colorsArr[0];
    for ($i = 0; $i < count($colorsArr); $i++) {
        if ($firstColor == $colorsArr[$i]) {
            echo '<div class="productColor activeColor" id="' . $colorsArr[$i] . '" data-productID="' . $productId . '" style="background-color:' . $colorsArr[$i] . '; width:50px; height:50px;"></div>';
        } else {
            echo '<div class="productColor " id="' . $colorsArr[$i] . '" data-productID="' . $productId . '" style="background-color:' . $colorsArr[$i] . '; width:50px; height:50px;"></div>';
        }
    }
}

function wc_insertAttributeSizeSingleProduct()
{
    global $product;
    $sizes = $product->get_attribute('size');
    $productId = $product->get_id();
    $sizesArr = explode(",", $sizes);
    $firstSize = $sizesArr[0];
    for ($i = 0; $i < count($sizesArr); $i++) {
        if ($firstSize == $sizesArr[$i]) {
            echo '<div class="productSize activeSize m-2 d-flex justify-content-center align-items-center" data-variationId="1"  data-productID="' . $productId . '" id="' . $sizesArr[$i] . '" style="width:50px; height:50px;">' . $sizesArr[$i] . '</div>';
        } else {
            echo '<div class="productSize m-2 d-flex justify-content-center align-items-center" data-variationId="1" data-productID="' . $productId . '" id="' . $sizesArr[$i] . '" style="width:50px; height:50px;">' . $sizesArr[$i] . '</div>';
        }
    }
}

// display atributes in list
function listOfAttributes()
{
    $arrayOfAttributes = wc_get_attribute_taxonomies();
    foreach ($arrayOfAttributes as $val) {
        $title = $val->attribute_name; ?>
        <p class="listAttributes-title"><?php echo $title ?></p>
        <?php
        $terms = get_terms('pa_' . $title); ?>
    <?php
        if (!empty($terms) && !is_wp_error($terms)) {
            echo '<div class="listAttributes">';
            foreach ($terms as $term) {
                if ($title == 'color') {
                    echo '<div style="background-color:' . $term->name . '" class="pa_' . $title . '" id="' . $term->name . '"></div>';
                } else {
                    echo '<div class="pa_' . $title . '" id="' . $term->name . '"> ' . $term->name . '</div>';
                }
            }
            echo '</div>';
        }
    }
}


add_action('woocommerce_before_checkout_form', 'displays_cart_products_feature_image');
function displays_cart_products_feature_image()
{
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        if (!empty($product)) {
            // $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'single-post-thumbnail' );
            echo $product->get_image();

            // to display only the first product image uncomment the line bellow
            // break;
        }
    }
}

// Count delivery cost
function sendDataToDeliveryCost()
{
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $quantity = $cart_item['quantity'];
        // if ($quantity < $cart_item['quantity']) {
        //     $quantity = $cart_item['quantity'];
        // }
        $weight = $product->weight;
        $height = $product->height;
        $totalWeight += ($weight * $quantity);
        $totalHeight += ($height * $quantity);
        $length = 0;
        $width = 0;
        if ($length < $product->length) {
            $length = $product->length;
        }
        if ($width < $product->width) {
            $width = $product->width;
        }
    }
    $totalPrice = WC()->cart->cart_contents_total;
    $datasend = ["width" => $width, "height" => $totalHeight, "length" => $length, "price" => $totalPrice, "weight" => $totalWeight];
    echo json_encode($datasend, JSON_FORCE_OBJECT);
    die();
}
add_action('wp_ajax_getDataDelivery', 'sendDataToDeliveryCost');
add_action('wp_ajax_nopriv_getDataDelivery', 'sendDataToDeliveryCost');


//Create order dynamically

add_action('wp_ajax_deliveryAttributes', 'create_order');
add_action('wp_ajax_nopriv_deliveryAttributes', 'create_order');
add_action('woocommerce_before_checkout_form', 'create_order');
function create_order()
{
    global $woocommerce;
    $firstName = $_REQUEST['firstName'];
    $secondName = $_REQUEST['secondName'];
    $phone = $_REQUEST['phone'];
    $email = $_REQUEST['email'];
    $city = $_REQUEST['city'];
    $department = $_REQUEST['department'];

    $address = array(
        'first_name' => $firstName,
        'last_name'  => $secondName,
        'email'      => $email,
        'phone'      => $phone,
        'address_1'  => $department,
        'city'       => $city,
    );


    // Now we create the order
    $order = wc_create_order();
    $cartItems = WC()->cart->get_cart();
    // The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
    foreach ($cartItems as $cart_item) {
        $productID = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];
        $variation = $cart_item['variation'];
        $args = array(
            'variation'    => $variation,
        );
        $order->add_product(wc_get_product($productID), $quantity, $args); // Use the product IDs to add
    }

    // Set addresses
    $order->set_address($address, 'billing');
    $order->set_address($address, 'shipping');

    // Calculate totals

    $order->calculate_totals();
    $order->update_status('Completed', 'Order created dynamically - ', true);
    $woocommerce->cart->empty_cart();
    if (WC()->cart->get_cart_contents_count() == 0) {
        echo "succsess";
    }
    wp_die();
}


// Get variation images by color
add_action('wp_ajax_getVariationImage', 'getVariationImage');
add_action('wp_ajax_nopriv_getVariationImage', 'getVariationImage');


function getVariationImage()
{
    // header('Content-Type: application/json');
    $product_id = $_REQUEST['productId'];
    $color = $_REQUEST['color'];
    $size = $_REQUEST['size'];
    /* Get variation attribute based on product ID */
    $product = new WC_Product_Variable($product_id);
    $variations = $product->get_available_variations();
    foreach ($variations as $variation) {

        if ($variation['attributes']['attribute_pa_color'] == $color && $variation['attributes']['attribute_pa_size'] == $size) {
            $url = $variation['image']['url'];
            $price = $variation['display_price'];
            $quantityInStock = $variation['max_qty'];
            $arr = ["url" => $url, "price" => $price, "quantityInStock" => $quantityInStock];
            echo (json_encode($arr, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            die();
            return;
        }
    }
}

// Get variation size and color quantity
add_action('wp_ajax_getVariationSizeQuantity', 'getVariationSizeQuantity');
add_action('wp_ajax_nopriv_getVariationSizeQuantity', 'getVariationSizeQuantity');


function getVariationSizeQuantity()
{
    // header('Content-Type: application/json');
    $product_id = $_REQUEST['productId'];
    $color = $_REQUEST['color'];
    $size = $_REQUEST['size'];
    /* Get variation attribute based on product ID */
    $product = new WC_Product_Variable($product_id);
    $variations = $product->get_available_variations();
    foreach ($variations as $variation) {

        if ($variation['attributes']['attribute_pa_color'] == $color && $variation['attributes']['attribute_pa_size'] == $size) {
            $variationId = $variation['variation_id'];
            $price = $variation['display_price'];
            $quantityInStock = $variation['max_qty'];
            $arr = ["price" => $price, "quantityInStock" => $quantityInStock, 'variation_id' => $variationId];
            echo (json_encode($arr, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            die();
            return;
        }
    }
}


// Add to cart from single product page
add_action('wp_ajax_addToCartSinglePage', 'addToCartSinglePage');
add_action('wp_ajax_nopriv_addToCartSinglePage', 'addToCartSinglePage');
function addToCartSinglePage()
{
    $product_id = $_REQUEST['productId'];
    $color = $_REQUEST['color'];
    $size = $_REQUEST['size'];
    $quantity = $_REQUEST['quantity'];
    $product = new WC_Product_Variable($product_id);
    $variations = $product->get_available_variations();
    foreach ($variations as $variation) {
        if ($variation['attributes']['attribute_pa_color'] == $color && $variation['attributes']['attribute_pa_size'] == $size) {
            $variation_id = $variation['variation_id'];
        }
    }
    $variation = ['color' => $color, "size" => $size];
    // $WC_Cart = new WC_Cart();
    // $WC_Cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);

    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation) && 'publish' === $product_status) {

        do_action('woocommerce_ajax_added_to_cart', $product_id);

        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }
        echo WC()->cart->get_cart_contents_count();
        //WC_AJAX::get_refreshed_fragments();
    } else {

        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        );

        echo wp_send_json($data);
    }


    die();
}


// get single product gallery
add_action('wp_ajax_singleProductGallery', 'singleProductGallery');
add_action('wp_ajax_nopriv_singleProductGallery', 'singleProductGallery');
function singleProductGallery()
{
    $product_id = $_REQUEST['productId'];
    $product = new WC_Product_Variable($product_id);


    $attachment_ids = $product->get_gallery_image_ids();

    foreach ($attachment_ids as $attachment_id) {
        $image[] = wp_get_attachment_image($attachment_id, 'custom-size');
    }


    echo json_encode($image, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    die();
}
// change size for the gallery
if (function_exists('add_image_size')) {
    add_image_size('custom-size', 500, 700);
}

// Add comments 
add_action('wp_ajax_singleProductComments', 'singleProductComments');
add_action('wp_ajax_nopriv_singleProductComments', 'singleProductComments');
function singleProductComments()
{

    $time = current_time('mysql');
    $postId = $_REQUEST['postId'];
    $comment_author = $_REQUEST['comment_author'];
    $comment_content = $_REQUEST['comment_content'];
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $comment_agent = $_SERVER['HTTP_USER_AGENT'];

    $data = [
        'comment_post_ID' => $postId,
        'comment_author' => $comment_author,
        'comment_content' => $comment_content,
        'comment_type' => '',
        'comment_parent' => 0,
        'comment_author_IP' => $ip,
        'comment_agent' => $comment_agent,
        'comment_date' => $time,
        'comment_approved' => 1,
    ];

    wp_insert_comment($data);
    echo "done";
    die();
}

// Search form
add_action('wp_ajax_searchProductForm', 'searchProductForm');
add_action('wp_ajax_nopriv_searchProductForm', 'searchProductForm');
function searchProductForm()
{
    $searchKeyword = $_REQUEST['searchText'];
    $args = array(
        's=' => $searchKeyword,
        // 'fields' => 'ids'
    );
    $query = new WP_Query('s=' . $searchKeyword);

    ?>

    <?php
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class=" col-lg-3 col-md-4 release-item">
                <a class="mt-2" href="<?php the_permalink() ?>"> <?php woocommerce_template_loop_product_thumbnail() ?></a>
                <a href="<?php the_permalink() ?>">
                    <p class="item-name"><?php the_title() ?></p>
                </a>
                <p class="item-color"><?php wc_insertAttributeColor() ?></p>
                <p class="item-color"><?php wc_insertAttributeSize() ?></p>
                <p class="item-price"><?php woocommerce_template_loop_price() ?></p>
            </div>
<?php
        endwhile;
        wp_reset_postdata();
    else :
        echo 'Нажаль за вашим запитом нічого не знайдено. Спробуйте змінити його';
    endif;

    die;
}

// Change href in back to shop button when cart is empty
function change_empty_cart_button_url()
{
    return '/shop/';
}
add_filter('woocommerce_return_to_shop_redirect', 'change_empty_cart_button_url');
