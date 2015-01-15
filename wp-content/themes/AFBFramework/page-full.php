<?php
/*
Template Name: Full Width
*/
get_header(); ?>
    <div role="main" id="main">
        <section id="content">
            <div class="container">
            <?php if(have_posts()): while(have_posts()): the_post(); ?>
                <h2 class="page-title"><?php the_title(); ?></h2>
                <div class="page-content col-xs-12">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; endif; ?>
            </div>
        </section>
    </div>
<?php get_footer(); ?>