<?php
get_header();
?>
<div class="container">
    <div class="blue-line col-xs-12"></div>
</div>
<div class="clearfix"></div>
<div id="main" role="main" class="main-page-bg">
    <div class="container">
        <div class="content col-xs-12 col-sm-8">
            <div class="crnr tlcorner"></div>
            <div class="crnr trcorner"></div>
            <div class="crnr blcorner"></div>
            <div class="crnr brcorner"></div>
            <?php if (have_posts()): while (have_posts()): the_post(); ?> 
                <h1 class="page-title"><?php the_title(); ?></h1>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>
        </div>
        <aside class="sidebar col-xs-12 col-sm-4">
            <?php dynamic_sidebar('sidebar'); ?>
        </aside>
    </div>
</div>
</div>
<?php get_footer(); ?>