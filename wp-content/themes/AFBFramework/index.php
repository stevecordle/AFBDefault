<?php
/**
 * @package WordPress
 * @subpackage ThemeBrew
 */
get_header();
?>
<div id="main" role="main" class="">
    <div class="home-banner">
        <div class="container">
            <?php dynamic_sidebar('home-banner'); ?>
        </div>
    </div>
    <div class="home-image">
        <img src="<?php echo THEME_IMAGES; ?>/home-image.jpg" class="img-responsive center-block" />
    </div>
    <div class="home-slider hidden-xs">
        <div class="container">
            <div class="slider cycle-slideshow"
                 data-cycle-slides=".slide"
                 data-cycle-timeout="4000" 
                 data-cycle-swipe="true" 
                 data-cycle-fx="fade"
                 data-cycle-pager="#cycle-pager"
                 >
                     <?php foreach ($Ops['opt-slides'] as $slide): ?>
                    <div class="slide clearfix">
                        <div class="col-xs-12 col-sm-5">
                            <img src="<?php echo $slide['image']; ?>" class="img-responsive" alt="<?php echo $slide['title']; ?>"/>
                        </div>
                        <div class="col-xs-12  col-sm-7 slide-content">
                            <h4 class="slide-title"><?php echo $slide['title']; ?></h4>
                            <p><?php echo $slide['description']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-xs-12 col-sm-5">
                <div id="cycle-pager"></div>
            </div>
        </div>
    </div>

    <section class="home-content container">
        <div class="crnr tlcorner"></div>
        <div class="crnr trcorner"></div>
        <div class="crnr blcorner"></div>
        <div class="crnr brcorner"></div>
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            <h3 class="row">WHAT'S UP?</h3>
            <span class="row">NEWS & EVENTS</span>
            <!-- POST LIST -->
            <div class="row">
                <?php $q = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 4)); ?>
                <?php while ($q->have_posts()) : $q->the_post(); ?>
                    <article class="col-xs-12 col-sm-6 news-item">
                        <img src="http://placehold.it/170x125" class="img-responsive pull-left"/>
                        <?php the_content(); ?>
                    </article>
                <?php endwhile; ?>
            </div>
            <!-- POST LIST -->
        </div>
        <div class="divider col-xs-12 col-sm-10 col-sm-offset-1"></div>
        <div class="home-bottom">
            <?php dynamic_sidebar('home-bottom'); ?>
        </div>
    </section>
</div>
<?php get_footer(); ?>