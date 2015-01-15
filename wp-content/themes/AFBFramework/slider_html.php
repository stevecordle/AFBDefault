        <section id="slider" class="cycle-slideshow"
            data-cycle-fx="fade"
            data-cycle-slides="> .slider-item"
            data-cycle-center-horz="true"
            data-cycle-auto-height="container"
        >
            <?php while($slider->have_posts()): $slider->the_post(); ?>
            <?php $sliderLink = get_post_meta($post->ID, '_tb_slider_link', true); ?>
                <a class="slider-item img-responsive" href="<?php echo ($sliderLink)? $sliderLink : '#'; ?>">
                    <img alt="<?php echo get_the_title(); ?>" src="<?php echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>" class="img-responsive" />
                </a>
            <?php endwhile; ?>
        </section>