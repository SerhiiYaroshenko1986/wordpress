<?php

/**
 * Footer 
 * 
 * PHP version 7.2
 * 
 * @category  Template_Part
 * @package   Theme
 * @author    Original Author <author@example.com>
 * @copyright 1997-2005 The PHP Group
 * @license   http://www.php.net/license/3_01.txt  PHP License 3.01
 * @link      http://pear.php.net/package/PackageName
 */


?>

<footer class="container-fluid">
    <div class="row">
        <?php
        global $template;
        if (basename($template) === 'front-page.php') {
            get_template_part('template-parts/mission-footer/mission');
        }
        ?>

        <div class="col-md-12 footer-social">
            <div class="footer-social-title">
                <?php the_field('socila_title', 'option') ?>
            </div>
            <a href="<?php the_field('social_instagram-url', 'option') ?>" target="_blank"><i class="fa fa-instagram"></i></a>
            <a href="<?php the_field('social_facebook-url', 'option') ?>" target="_blank"><i class="fa fa-facebook-f"></i></a>
            <a href="<?php the_field('social_twitter-url', 'option') ?>" target="_blank"><i class="fa fa-twitter"></i></a>
            <a href="<?php the_field('social_youtube-url', 'option') ?>" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>

        </div>
    </div>
    <div class="author">
        © 2020 made with love by <a href="https://www.linkedin.com/in/serhii-yaroshenko-566800195/" target="_blank"> Serhii Yaroshenko</a> | All Rights Reserved. | Be a visionary.
    </div>
</footer>


<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<?php wp_footer(); ?>
</body>

</html>