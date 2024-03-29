<?php global $Ops; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php wp_title('&laquo;', true, 'right'); ?></title>
        <?php wp_head(); ?> 
    </head>
    <body>
        <div id="page" class="mm-page"> <!-- the wrapper start -->
            <nav id="mmobile-nav" class="mm-menu hidden-md hidden-lg">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'mobile-menu',
                    'container' => false,
                    'menu_class' => '',
                    'depth' => 4,
                ));
                ?>
            </nav>
            <div class="hidden-md hidden-lg visible-xs row mobile-trigger-wrap">
                <a id="mobile-menu-trigger" class="icon icon-bars" href="#mmobile-nav"> Menu</a>
            </div>
            <div class="container">
            <header>
                <div class="col-xs-12 col-sm-4 col-sm-offset-8 header-logos">
                    <?php dynamic_sidebar('header-phone'); ?>
                </div>
                <div class="col-xs-12 col-sm-8">
                    <nav class="navbar navbar-default">
                        <div class="collapse navbar-collapse navbar-responsive-collapse">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'main-menu',
                                'container' => false,
                                'menu_class' => 'nav navbar-nav',
                                'depth' => 3,
                                'walker' => new BootstrapWalker()
                            ));
                            ?>
                    </nav>
                </div>
            </header>