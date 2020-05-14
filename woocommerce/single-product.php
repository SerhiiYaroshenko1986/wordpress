<?php

/**
 * The Template for displaying all single products
 *
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

get_header();

?>


<div class="container-fluid item-container">
	<div class="row ">
		<?php while (have_posts()) : ?>
			<?php the_post(); ?>
			<?php
			global $product;
			$product_id = $product->get_id();
			$variations = $product->get_available_variations();
			?>
			<div class=" col-lg-5 col-md-5 release-item">
				<img id="productImage" class="productImage img-fluid" src="<?php echo $variations[0]['image']['url'] ?>" alt="<?php echo $product->get_name(); ?>">
			</div>
			<div class="col-md-7 item-wrap-details">

				<p class="item-name"><?php the_title() ?></p>

				<p class="item-price"><?php echo $variations[0]['display_price'] ?> ₴</p>

				<div class="productColors d-flex">
					<?php wc_insertAttributeColorSingleProduct() ?>
				</div>
				<div class="d-flex"><?php wc_insertAttributeSizeSingleProduct() ?></div>
				<?php if ($variations[0]['max_qty'] > 0) {
				?>
					<div class="quantityInStockContainerFirstLoad"><span class="quantityInStock" id="<?php echo $variations[0]['max_qty'] ?>"><?php echo $variations[0]['max_qty'] ?></span> в наявності </div>
					<div class="quantityToOrderContainerFirstLoad">
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
				<div class="item-description"><?php echo $product->get_description(); ?></div>
			<?php
		//print_r($variations = $product->get_available_variations());
		endwhile; ?>
			<div>
				<div>
					<button id="addToCartSinglePage" class="addToCartSinglePage d-flex justify-content-center" <?php if ($variations[0]['max_qty'] <= 0) {
																													echo 'disabled=true style="cursor:not-allowed"';
																												}
																												?>>
						<div style="display: none" class="lds-ring">
							<div></div>
							<div></div>
							<div></div>
							<div></div>
						</div><span class="addToCartSinglePage-btnText">Додати до кошика</span>
						<div class="addToCartSuccsess" style="display:none">
							<svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
								<circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
								<path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" /></svg>
						</div>
					</button>
				</div>
			</div>
			</div>

	</div>
</div>
<div class="slider-wrap" style="display: none">
	<div class="slider container">

	</div>
	<span class="slider-close-btn">
		<img class="search-field-close-btn" src="https://img.icons8.com/windows/32/000000/delete-sign.png" />
	</span>
</div>
<div class="container-fluid itemCommentsContainer mt-2">
	<div class="row">
		<div class="col-md-5">
			<h3>Коментарі</h3>
			<div class="ifCommentsNone">
				<?php
				$productId = $product->get_id();
				$comments = get_comments(array(
					'post_id' => $productId,
				));
				if (count($comments) == 0) {
					echo "Будьте першим і залиште свій відгук про товар";
				}
				?>
			</div>
			<form action="" method="post" id="comments-form" class="item-comments-form">
				<input class="" type="text" name="name" id="name" placeholder="Ваше ім'я" require>
				<textarea name="message" id="message" cols="30" rows="3" placeholder="Ваш відгук" require></textarea>
				<div class="errorCommentsForm" style="color:red"></div>
				<button type="submit" id="addCommentSubmit"> Відправити</button>
			</form>
			<div class="comments">
				<?php
				$productId = $product->get_id();
				$comments = get_comments(array(
					'post_id' => $productId,
				));
				if (count($comments) > 0) {
					foreach ($comments as $comment) {
				?>

						<div class="comments-container-inner mt-2">
							<span class="comment-author"><?php echo $comment->comment_author ?></span>
							<span class="comment-date"><?php echo date('d m Y', strtotime($comment->comment_date)); ?></span>
						</div>
						<div class="comment-text"><?php echo $comment->comment_content ?></div>


				<?php
					}
				}
				?>
				<!-- <div class="navigation">
		<?php
		$args = array(
			'base'         => '%_%',
			'format'       => '?page=%#%',
			'total'        => 1,
			'current'      => 0,
			'show_all'     => False,
			'end_size'     => 1,
			'mid_size'     => 2,
			'prev_next'    => True,
			'prev_text'    => __('« Previous'),
			'next_text'    => __('Next »'),
			'type'         => 'plain',
			'add_args'     => False,
			'add_fragment' => '',
			'before_page_number' => '',
			'after_page_number'  => ''
		);
		echo paginate_links($args) ?>
	</div> -->
			</div>

		</div>
		<div class="col-md-7 col-sm-12">
			<h3>Можливо Вас зацікавить:</h3>
			<div class="slider_probably_buy">
				<?php

				$productId = $product->get_id();
				$categories = wp_get_post_terms($productId, 'product_cat');
				foreach ($categories as $category) {
					if ($category->parent == 0) {
						$categoryDone = $category->slug;
					}
				}


				$args = array(
					'post_type' => 'product',
					'posts_per_page' => 4,
					'product_cat' => $categoryDone,
					'post__not_in' => [$productId],
					'orderby' => 'date',
					'order' => 'DESC'
				);
				$loop = new WP_Query($args);
				if ($loop->have_posts()) {
					while ($loop->have_posts()) : $loop->the_post();
				?>
						<div class="m-2 d-flex align-items-center justify-content-center flex-column single-page-gallery">
							<a href="<?php the_permalink() ?>"> <?php woocommerce_template_loop_product_thumbnail() ?></a>
							<a href="<?php the_permalink() ?>">
								<p class="item-name"><?php the_title() ?></p>
							</a>
							<p class="item-color"><?php wc_insertAttributeColor() ?></p>
							<p class="item-color"><?php wc_insertAttributeSize() ?></p>
							<p class="item-price"><?php woocommerce_template_loop_price() ?></p>

						</div>


				<?php endwhile;
				} else {
					echo __('No products found');
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
</div>
</div>


<?php
get_footer();
?>