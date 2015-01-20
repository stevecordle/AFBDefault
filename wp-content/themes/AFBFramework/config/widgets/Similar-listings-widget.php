<?php
/**
 * WIDGET :: Recent Property
 *
 * @package     EPL
 * @subpackage  Widget/Recent_Property
 * @copyright   Copyright (c) 2014, Merv Barrett
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

class AFB_Similiar_Widget_Recent_Property extends WP_Widget {

    function __construct() {
        parent::WP_Widget(false, $name = __('AFB Similiar Properties', 'epl'));
    }

    function widget($args, $instance) {

		$defaults = array(
						'title'		=>	'',
						'types'		=>	'property',
						'featured'	=>	0,
						'status'	=>	'any',
						'display'	=>	'image',
						'image'		=>	'thumbnail',
						'archive'	=>	0,
						'order_rand'=>	0,
						'd_title'	=>	0,
						
						'more_text'	=>	'Read More',
						'd_excerpt'	=>	'off',
						'd_suburb'	=>	'on',
						'd_street'	=>	'on',
						'd_price'	=>	'on',
						'd_more'	=>	'on',

						'd_icons'	=>	'none',
						'p_number'	=>	1,
						'p_skip'	=>	0
					);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']);
		$display	= $instance['display'];
		$image		= $instance['image'];
		$archive	= $instance['archive'];
		
		$d_title	= $instance['d_title'];

		$more_text	= $instance['more_text'];
		$d_excerpt	= $instance['d_excerpt'];
		$d_suburb	= $instance['d_suburb'];
		$d_street	= $instance['d_street'];
		$d_price	= $instance['d_price'];
		$d_more		= $instance['d_more'];
		
		$d_icons	= $instance['d_icons'];
		
		$p_number	= 3; //$instance['p_number'];
		$p_skip		= $instance['p_skip'];
		$types		= $instance['types'];
		$featured	= $instance['featured'];
		$status		= $instance['status'];
		$order_rand	= 'on'; //instance['order_rand'];
	
		if ( $types == '' ) { $types = 'property'; }
		if ( $p_number == '' ) { $p_number = 1; }
		if ( $d_icons == 'beds/baths' ) {
			$d_icons = 'bb';
		}
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		
		if ( $featured == 'on' ) {
			$args = array(
				'post_type' => $types, 
				'showposts' => $p_number,
				'offset'	=> $p_skip,
				'meta_query' => array(
					array(
						'key' => 'property_featured',
						'value' => 'yes'
					)
				)
			);
		} elseif ( $archive == 'on' && is_post_type_archive() ) {
			$get_types = get_post_type( $post );
			$args = array(
				'post_type' => $get_types, 
				'showposts' => $p_number,
				'offset'	=> $p_skip
			);
		} else {
			if ( $status == 'Current' ) {
				$args = array(
					'post_type' => $types, 
					'showposts' => $p_number,
					'offset'	=> $p_skip,
					'meta_query' => array(
						array(
							'key' => 'property_status',
							'value' => 'current'
						)
					)
				);

			} elseif ( $status == 'Sold' ) {
				$args = array(
					'post_type' => $types, 
					'showposts' => $p_number,
					'offset'	=> $p_skip,
					'meta_query' => array(
						array(
							'key' => 'property_status',
							'value' => 'sold'
						)
					)
				);

			} elseif ( $status == 'leased' ) {
				$args = array(
					'post_type' => $types, 
					'showposts' => $p_number,
					'offset'	=> $p_skip,
					'meta_query' => array(
						array(
							'key' => 'property_status',
							'value' => 'leased'
						)
					)
				);

			} else {
				$args = array(
					'post_type' => $types, 
					'showposts' => $p_number,
					'offset'	=> $p_skip
				);
			}
		}
		if ( $order_rand == 'on' ) {
			$args['orderby'] = 'rand';
		}
                $query = new WP_Query($args);
        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $meta = get_post_meta(get_the_ID());
                ?>
                <div id="post-<?php the_ID(); ?>" class="epl-widget afb-listing-widget property-widget-image clearfix">
                    <div class="col-xs-4">
                        <div class="row">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('admin-list-thumb', array('class' => 'img-responsive center-block')); ?>
                            </a>
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <?php echo $meta['property_address_street'][0]; ?><br/>
                        <?php echo $meta['property_address_suburb'][0]; ?>,  <?php echo $meta['property_address_state'][0]; ?>  <?php echo $meta['property_address_postal_code'][0]; ?>
                        <span class="widget-price center-block"><?php echo $meta['property_price_view'][0]; ?></span>
                    </div>
                </div>

                <?php
                wp_reset_query();
            endwhile;
        endif;
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        return $instance;
    }

    function form($instance) {
        ?>
        <p>Widget for listing Similar Properties</p>
        <?php
    }

}

add_action('widgets_init', create_function('', 'return register_widget("AFB_Similiar_Widget_Recent_Property");'));
