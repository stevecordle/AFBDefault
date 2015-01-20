<?php
function afb_custom_post_testimonials() {

	$labels = array(
		'name'                => _x( 'Testimonials', 'isites' ),
		'singular_name'       => _x( 'Testimonial', 'isites' ),
		'menu_name'           => __( 'Testimonials', 'isites' ),
		'parent_item_colon'   => __( 'Testimonial:', 'isites' ),
		'all_items'           => __( 'All Testimonials', 'isites' ),
		'view_item'           => __( 'View Testimonial', 'isites' ),
		'add_new_item'        => __( 'Add New Testimonial', 'isites' ),
		'add_new'             => __( 'Add Testimonial', 'isites' ),
		'edit_item'           => __( 'Edit Testimonial', 'isites' ),
		'update_item'         => __( 'Update Testimonial', 'isites' ),
		'search_items'        => __( 'Search Testimonial', 'isites' ),
		'not_found'           => __( 'Testimonial Not found', 'isites' ),
		'not_found_in_trash'  => __( 'Testimonial Not found in Trash', 'isites' ),
	);
	$args = array(
		'label'               => __( 'testimonials', 'isites' ),
		'description'         => __( 'Testimonial Postings', 'isites' ),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'revisions'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	);
	register_post_type( 'testimonials', $args );

}

add_action( 'init', 'afb_custom_post_testimonials', 0 );

function change_default_title_testimonials( $title ){
    $screen = get_current_screen();
    if ( 'testimonials' == $screen->post_type ){
        $title = 'Enter Person\'s name here';
    }
    return $title;
}
 
add_filter( 'enter_title_here', 'change_default_title_testimonials' );