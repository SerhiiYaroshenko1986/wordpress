<?php

/**
 * The Template for displaying all single products
 *
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

get_header('shop');

?>


<div class="container-fluid">
	<div class="row">
		<?php while (have_posts()) : ?>
			<?php the_post(); ?>
			<?php
			global $product;
			$variations = $product->get_available_variations();

			?>
			<div class=" col-lg-5 col-md-5 release-item">
				<a class="mt-2" href="<?php the_permalink() ?>"><img id="productImage" src="<?php echo $variations[0]['image']['url'] ?>" alt="<?php echo $product->get_name(); ?>"> </a>
			</div>
			<div class="col-md-7">
				<a href="<?php the_permalink() ?>">
					<p class="item-name"><?php the_title() ?></p>
				</a>
				<p class="item-price"><?php echo $variations[0]['display_price'] ?> ₴</p>
				<div class="productColors d-flex">
					<?php wc_insertAttributeColorSingleProduct() ?>
				</div>
				<div class="d-flex"><?php wc_insertAttributeSizeSingleProduct() ?></div>
				<?php if ($variations[0]['max_qty'] > 0) {
				?>
					<div class="quantityInStockContainer"><span class="quantityInStock" id="<?php echo $variations[0]['max_qty'] ?>"><?php echo $variations[0]['max_qty'] ?></span> в наявності </div>
					<div class="quantityToOrderContainer">
						<button type="button" id="sub" class="sub">-</button>
						<input type="number" id="quantityToOrder" value="1" min="1" max="1" />
						<button type="button" id="add" class="add">+</button>
					</div>
				<?php } else { ?>
					<div class="firstLoadError">Нажаль товару немає в наявності оберіть будь ласка інші варіаціїї товару</div>
				<?php } ?>
				<div class="quantityInStockContainer" style="display:none"><span class="quantityInStock" id="<?php echo $variations[0]['max_qty'] ?>"><?php echo $variations[0]['max_qty'] ?></span> в наявності
					<div class="quantityToOrderContainer">
						<button type="button" id="sub" class="sub">-</button>
						<input type="number" id="quantityToOrder" value="1" min="1" max="1" />
						<button type="button" id="add" class="add">+</button>
					</div>
				</div>
				<div class="quantityInStockContainerError" style="display:none">Нажаль товару немає в наявності оберіть будь ласка інші варіаціїї товару</div>
			<?php echo $product->get_description();
		//print_r($variations = $product->get_available_variations());
		endwhile; ?>
			<div>
				<div><button id="addToCartSinglePage" class="addToCartSinglePage" <?php if ($variations[0]['max_qty'] <= 0) {
																						echo 'disabled=true style="cursor:not-allowed"';
																					} ?>>
						<div style="display: none" class="lds-ring">
							<div></div>
							<div></div>
							<div></div>
							<div></div>
						</div><span class="addToCartSinglePage-btnText">Додати до кошика</span>

					</button></div>
			</div>
			</div>


	</div>
</div>
<?php
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');
?>

<?php
/**
 * woocommerce_sidebar hook.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');
?>

<?php
get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
