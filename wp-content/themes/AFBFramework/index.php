<?php
/**
 * @package WordPress
 * @subpackage ThemeBrew
 */
get_header();
?>
<div id="main" role="main" class="">
    <div class="slider-wrap hidden-xs">
        <div class="container">
            <div class="col-xs-12 col-sm-5 slider-left">
                <?php dynamic_sidebar('home-slider-left'); ?>
            </div>
            <div class="col-xs-12 col-sm-7 slider-right">
                <div class="slider cycle-slideshow"
                     data-cycle-slides="> div.slide"
                     data-cycle-timeout="12000" 
                     data-cycle-swipe="true" 
                     data-cycle-fx="fade"
                     data-cycle-auto-height="672:275"
                     >
                         <?php foreach ($Ops['opt-slides'] as $slide): ?>
                        <div class="slide">
                            <div class="col-xs-12" style="padding: 0;">
                                <img src="<?php echo $slide['image']; ?>" class="img-responsive" alt="<?php echo $slide['title']; ?>"/>
                            </div>
                            <div class="col-xs-12 slide-content row">
                                <h1 class="slide-title"><?php echo $slide['title']; ?></h1>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <section class="home-content-top-wrap">
        <div class="container">
            <div class="col-xs-12 home-content-top">
                <div class="col-xs-12 col-sm-4 home-cta-left">
                    <?php dynamic_sidebar('home-top-left'); ?>
                </div>
                <div class="col-xs-12 col-sm-4 home-cta-middle">
                    <?php dynamic_sidebar('home-top-middle'); ?>
                </div>
                <div class="col-xs-12 col-sm-4 home-cta-right">
                    <?php dynamic_sidebar('home-top-right'); ?>
                </div>
            </div>
    </section>
    <section class="home-content">
        <div class="container">   
            <div class="col-xs-12 col-sm-8 home-content-left">
                <?php dynamic_sidebar('home-content-left'); ?>
            </div>
            <div class="col-sm-1 home-content-divider hidden-xs">
                <div class="center-block center-text border"></div>
            </div>
            <div class="col-xs-12 col-sm-3 home-content-right home-sidebar">
                <?php dynamic_sidebar('home-sidebar'); ?>
            </div>
        </div>
    </section>
</div>
<?php get_footer(); ?>