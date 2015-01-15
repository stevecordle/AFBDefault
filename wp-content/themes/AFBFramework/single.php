
<?php get_header(); ?>
    <section id="slider">
        <?php putRevSlider("home"); ?>
    </section>
    <div class="clearfix"></div>
    <div id="main" role="main">
        <section class="container home-content">
        <?php if(have_posts()): while(have_posts()): the_post(); ?>
            <?php if(has_post_thumbnail()): ?>
            <div class="col-xs-12 col-sm-5">
                <?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?>
            </div>
            <div class="col-xs-12 col-sm-7">
                <article class="content">
                  <header>
                    <h2 class="col-xs-12 page-title"><?php the_title(); ?></h2>
                    <time datetime="<?php the_time('Y-m-d')?>"><?php the_time('l, F jS, Y') ?></time>
                  </header>
                  <?php the_content(); ?>
                  <footer>
                    <?php the_tags('Tags: ', ', ', '<br />'); ?>
                    Posted in <?php the_category(', ') ?>
                    | <?php edit_post_link('Edit', '', ''); ?>
                  </footer>
                </article>
            </div>
            <?php else: ?>
                <article class="content col-xs-12">
                  <header>
                    <h2 class="col-xs-12 page-title"><?php the_title(); ?></h2>
                    <time datetime="<?php the_time('Y-m-d')?>"><?php the_time('l, F jS, Y') ?></time>
                  </header>
                <?php the_content(); ?>
                  <footer>
                    <?php the_tags('Tags: ', ', ', '<br />'); ?>
                    Posted in <?php the_category(', ') ?>
                    | <?php edit_post_link('Edit', '', ''); ?>
                  </footer>
                </article>
            <?php endif; ?>
        <?php endwhile; endif; ?>
        </section>
    </div>
<?php get_footer(); ?>