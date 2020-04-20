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
    <div class="text-center"><?php the_title() ?></div>
    <div class="row">
        <div class="col-md-7">
            <div>Заповніть дані для отримання вашого замовлення</div>
            <form action="" method="post" id="checkout-form">
                <div>
                    <label for="name">Ім'я
                    </label><br>
                    <input id="firstName" type="text" name="firstName" require>
                    <span id="firstNameError" style="display: none; color:red">Ім'я повинно містити тільки літери та буде не коротше 3-х симаолів</span>
                </div>
                <div>
                    <label for="name">Прізвище
                    </label><br>
                    <input id="secondName" type="text" name="secondName" require>
                    <span id="secondNameError" style="display: none; color:red">Прізвище повинно містити тільки літери та буде не коротше 3-х симаолів</span>
                </div>
                <div>
                    <label for="phone">Номер телефону
                    </label><br>
                    <input require id="phone" type="phone" name="phone">
                    <span id="phoneError" style="display: none; color:red"> Номер телефону повинен містити тільки цифри </span>
                </div>
                <div>
                    <label for="email">Електронна пошта
                    </label><br>
                    <input id="email" require type="email" name="email">
                    <span id="emailError" style="display: none; color:red">Будь ласка введіть коректну електронну адресу</span>
                </div>

            </form>
            <div>
                Введіть назву міста та оберіть номер відділення нової пошти
            </div>
            <input id="city" type="text" placeholder="Введіть місто" />
            <p class="mt-3"><select style="display: none;" id="department" data-delivery="false"></select></p>
            <p class="countDelivery" style="display: none">Вартість доставки до Вас буде орієнтовно становити
                <span id="deliveryCost"></span> ₴. Точна ціна буде встановлена на момент відправки згідно тарифів перевізника </p>
            <div id="result" style="color:red"></div>
        </div>
        <div class="col-md-5">
            <div class="row">

                <?php
                foreach (WC()->cart->get_cart() as $cart_item) {
                    $product = $cart_item['data'];
                    $quantity = $cart_item['quantity'];
                    if (!empty($product)) {
                ?>
                        <div class="col-12 d-flex justify-content-start align-items-center">
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
                <div class="col-12 text-right">
                    <?php
                    echo WC()->cart->cart_contents_total
                    ?>
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
    <div class="text-center">
        <button id="checkout-button" type="submit" form="checkout-form">Відправити замовлення</button>
    </div>
</div>
<div class="checkoutSuccsess" style="display: none">
    <p>Дякуємо за покупку!!!</p>
    <p>Ваше замовлення знаходиться в обробці найближчим часом з вами зв'яжеться менеджер </p>
    <button onclick="location.href='/shop'">Повернутися до покупок</button>
</div>
<?php get_footer() ?>