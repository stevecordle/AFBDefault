<?php //

/************ SETUP Custom Post ************/
$parallax = new CP_Helper('Parallax Page');
/************ SETUP Custom Post ************/



/************ SETUP Meta Boxes ************/
$parallax->add_meta_box('Page Options', array(
    array(
        'name' => 'Page Height',
        'label' => 'Page Height',
        'type' => 'text',
        'metric' => 'px',
        'desc' => 'Minimum height in pixels'
    ),
    array(
        'name' => 'Background Color',
        'label' => 'Background Color',
        'type' => 'text',
        'desc' => 'Background color of page'
    )
));
/************ SETUP Meta Boxes ************/



/************ SETUP Taxonomies ************/

/************ SETUP Taxonomies ************/