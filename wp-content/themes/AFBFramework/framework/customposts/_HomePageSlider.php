<?php //
add_theme_support('post-thumbnails');
/************ SETUP Custom Post ************/
$home_slider = new CP_Helper('Home Page Slider');
/************ SETUP Custom Post ************/



/************ SETUP Meta Boxes ************/
$home_slider->add_meta_box('Slider Options', array(
    array(
        'name' => 'Slider Link',
        'label' => 'Slider Link',
        'type' => 'text',
        'desc' => 'Where the slider should link to'
    )
));
/************ SETUP Meta Boxes ************/



/************ SETUP Taxonomies ************/

/************ SETUP Taxonomies ************/



/************ SETUP Scripts ************/
function loadCycle(){
    wp_enqueue_script('cycle');
}

//add_action('wp_enqueue_scripts', 'loadCycle');
/************ SETUP Scripts ************/