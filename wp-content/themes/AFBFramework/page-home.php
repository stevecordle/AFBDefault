<?php if ( is_page('89') && ! post_password_required() ) { 
   wp_redirect('home-logged-in/'); exit; 
} ?>
<?php
get_header();
?>
<div class="container">
    <div class="blue-line col-xs-12"></div>
</div>
<div class="clearfix"></div>
<div id="main" role="main" class="main-page-bg">
    <section class="page-content">
        <div class="container">
            <?php if (have_posts()): while (have_posts()): the_post(); ?>
                    <h1 class="page-title col-xs-12"><?php the_title(); ?></h1>
                    <div class="col-xs-12">
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <?php
                endwhile;
            endif;
            ?>
    </section>
</div>
</div>
<?php get_footer(); ?>
