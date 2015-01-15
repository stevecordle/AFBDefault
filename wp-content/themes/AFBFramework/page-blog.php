<?php
/*
  Template Name: Blog Page
 */
get_header();
?>
<div class="clearfix"></div>
<section class="tagline-bar-wrapper">
    <div class="container">
        <div class="col-xs-12 tagline-bar">
            <?php dynamic_sidebar('tagline-bar'); ?>
        </div>
    </div>
</section>
<div id="main" role="main">
    <section class="container home-content">
        <h1 class="col-xs-12 page-title"><?php the_title(); ?></h1>
        <?php $q = new WP_Query(array('post_type' => 'post')); ?>
        <?php while ($q->have_posts()) : $q->the_post(); ?>
            <article class="content">
                <header>
                    <h2 class="col-xs-12 page-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <time class="col-xs-12" datetime="<?php the_time('Y-m-d') ?>"><?php the_time('l, F jS, Y') ?></time>
                </header>
                <div class="col-xs-12">
                    <?php the_content(); ?>
                </div>
                <footer class="col-xs-12">
                    <?php the_tags('Tags: ', ', ', '<br />'); ?>
                    Posted in <?php the_category(', ') ?>
                    | <?php edit_post_link('Edit', '', ''); ?>
                </footer>
            </article>
        <?php endwhile; ?>
    </section>
    <?php get_sidebar('blog'); ?>
</div>
<?php get_footer(); ?>





