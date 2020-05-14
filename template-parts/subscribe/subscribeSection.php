<section class="sign-up">
    <p class="sign-up_text"><?php the_field('content_text', 'option') ?></p>
    <button class="sign-up-button" id="subscribe-button">
        <p><?php the_field('button_title', 'option') ?></p>
    </button>
    <div class="subscribe-wrap">
        <div class="subscribe-form-container">
            <?php echo do_shortcode('[email-subscribers-form id="1"]') ?>
            <span class="close-btn-container"> <img class="search-field-close-btn" src="https://img.icons8.com/windows/32/000000/delete-sign.png" /></span>
        </div>
    </div>
</section>