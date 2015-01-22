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
                    <div class="col-xs-12 col-sm-9">
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3">
                        <?php dynamic_sidebar('page-sidebar'); ?>
                    </div>
                    <?php
                endwhile;
            endif;
            ?>
    </section>
</div>
</div>
<?php get_footer(); ?>