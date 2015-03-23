<?php
/**
 * @package WordPress
 * @subpackage ThemeBrew
 */
get_header();
?>
<div id="main" role="main" class="main">
    <section class="home-content">
        <div class="container">   
            <div class="col-xs-12 home-content-left">
                <?php dynamic_sidebar('home-content'); ?>
            </div>
        </div>
    </section>
</div>
<?php get_footer(); ?>