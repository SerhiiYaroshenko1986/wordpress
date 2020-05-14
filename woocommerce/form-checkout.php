<?php

/**
 * Template Name:Checkout Form
 */

if (!defined('ABSPATH')) {
    exit;
}
get_header();
?>
<div class="container-fluid checkout">
    <div class="text-center checkout-title"><?php the_title() ?></div>
    <div class="row">
        <div class="col-md-6">
            <div class="checkout-order-title">Заповніть дані для отримання вашого замовлення</div>
            <form action="" class="checkout-order-form" method="post" id="checkout-form">
                <div class="checkout-order-firstName">
                    <input id="firstName" placeholder="Ім'я отримувача" type="text" name="firstName" require>
                    <span id="firstNameError" style="display: none; color:red">Ім'я повинно містити тільки українські літери та буде не коротше 3-х символів</span>
                </div>
                <div class="checkout-order-secondName">
                    <input id="secondName" placeholder="Прізвище отримувача" type="text" name="secondName" require>
                    <span id="secondNameError" style="display: none; color:red">Прізвище повинно містити тільки українські літери та буде не коротше 3-х символів</span>
                </div>
                <div class="checkout-order-phone">
                    <input require id="phone" placeholder="Номер телефону" type="phone" name="phone">
                    <span id="phoneError" style="display: none; color:red"> Номер телефону повинен містити тільки цифри </span>
                </div>
                <div class="checkout-order-email">
                    <input id="email" placeholder="Електронна пошта" require type="email" name="email">
                    <span id="emailError" style="display: none; color:red">Будь ласка введіть коректну електронну адресу</span>
                </div>
            </form>
            <div class="checkout-delivery-title">
                Введіть назву міста та оберіть номер відділення нової пошти
            </div>
            <input class="checkout-delivery-input" id="city" type="text" placeholder="Введіть місто" />
            <p class="mt-3 checkout-delivery-departments"><select style="display: none;" id="department" data-delivery="false"></select></p>
            <p class="countDelivery" style="display: none">Вартість доставки до Вас буде орієнтовно становити
                <b><span id="deliveryCost"></span></b> ₴. Точна ціна буде встановлена на момент відправки згідно тарифів перевізника </p>
            <div id="result"></div>
        </div>
        <div class="col-md-6">
            <div class="row">

                <?php
                foreach (WC()->cart->get_cart() as $cart_item) {
                    $product = $cart_item['data'];
                    $quantity = $cart_item['quantity'];
                    if (!empty($product)) {
                ?>
                        <div class="col-12 d-flex justify-content-between align-items-center">
                            <p class="checkout-img mr-2 "><?php echo $product->get_image('custom-thumb'); ?>
                                <span class="checkout-quaintity">
                                    <span><?php echo $quantity ?></span>
                                </span>
                            </p>
                            <p class="checkout-name"><?php echo $product->name ?></p>
                            <p class="checkout-price"><?php echo $product->price ?> ₴</p>

                        </div>
                        <hr>
                <?php
                    }
                }
                ?>
            </div>
            <div class="row">
                <div class="col-12 text-right d-flex justify-content-between">
                    <div class="checkout-total-price"><b>Загалом: </b></div>
                    <div><b><?php
                            echo WC()->cart->cart_contents_total
                            ?> ₴</b>
                    </div>
                    <!-- <div class="col-12">
                            <form method="POST" action="https://www.liqpay.ua/api/3/checkout" accept-charset="utf-8">
                                <input type="hidden" name="data" value="eyJwdWJsaWNfa2V5IjoiaTAwMDAwMDAwIiwidmVyc2lvbiI6IjMiLCJhY3Rpb24iOiJwYXkiLCJhbW91bnQiOiIzIiwiY3VycmVuY3kiOiJVQUgiLCJkZXNjcmlwdGlvbiI6InRlc3QiLCJvcmRlcl9pZCI6IjAwMDAwMSJ9" />
                                <input type="hidden" name="signature" value="wR+UZDC4jjeL/qUOvIsofIWpZh8=" />
                                <input type="image" src="//static.liqpay.ua/buttons/p1ru.radius.png" />
                            </form>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <button class="checkout-button-submit" id="checkout-button" type="submit" form="checkout-form">Відправити замовлення</button>
    </div>
</div>
<div class="checkoutSuccsess" style="display: none">
    <p class="checkoutSuccsess-title">Дякуємо за покупку!!!</p>
    <p class="checkoutSuccsess-message">Ваше замовлення знаходиться в обробці найближчим часом з вами зв'яжеться менеджер </p>
    <button class="checkoutSuccsess-btn" onclick="location.href='/shop'">Повернутися до покупок</button>
</div>

<?php get_footer() ?>