<?php

class AFB_Testimonial_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
                'afb_testimonials', 'AFB Testimonials', array(
            'description' => 'Lists Testimonials from the custom post type "Testimonials'
                )
        );
        
        wp_register_script('testimonials-widget', trailingslashit(CONFIG_URL).'widgets/Testimonial-widget.js', array('jquery'));
        wp_enqueue_script('testimonials-widget');
        
        wp_register_style('testimonials-widget', trailingslashit(CONFIG_URL).'widgets/Testimonial-widget.css', array('main'));
        wp_enqueue_style('testimonials-widget');
    }

    function form($instance) {
        echo "<p>This widget displays Testimonials randomly.</p>";
    }

    function update($new_instance, $old_instance) {
        
    }

    function widget($args, $instance) {
        $argsQ = array(
            'post_type' => 'testimonials',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'caller_get_posts' => 1
        );

        $tQuery = new WP_Query($argsQ);
        if ($tQuery->have_posts()) {
            echo "<ul class='afb-testimonials-list'>";
            while ($tQuery->have_posts()) : $tQuery->the_post(); ?>
                <li>&ldquo;<?php echo get_the_content(); ?>&rdquo;</li>
            <?php endwhile;
            echo "</ul>";
        }
        wp_reset_query();
    }
}

function afb_register_widget_testimonial() {
    register_widget('AFB_Testimonial_Widget');
}

add_action('widgets_init', 'afb_register_widget_testimonial');
