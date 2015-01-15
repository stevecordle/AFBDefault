<?php if (! defined( 'ABSPATH' )) { header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!'); }

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utility
 *
 * @author scordle
 */
class Utility {
    
    public $systemInfo = array();
    
    public function __construct() {
        $this->loadSysInfo();
    }
    
    static function getPostImages($size = 'full'){
        global $post;
        $urls = array();

        $images = get_children(array(
            'post_parent' => $post->ID, 
            'post_status' => 'inheret',
            'post_type'   => 'attachment',
            'post_mime_type' => 'image'
        ));

        if(isset($images)){
            foreach($images as $image){
                $imgThumb = wp_get_attachment_image_src($image->ID, $size, false);
                $urls[] = $imgThumb[0];
            }  

            return $urls;
        }else{
            return false;
        }
    }

    /*
     * Retreive url for FIRST image attachment from a post
     */
    static function getFirstPostImage($echo = false, $size = 'full'){
        global $post;
        $urls = array();

        $images = get_children(array(
            'post_parent' => $post->ID, 
            'post_status' => 'inheret',
            'post_type'   => 'attachment',
            'post_mime_type' => 'image'
        ));
        $count = 1;
        if(isset($images)){
            foreach($images as $image){
                if($count == 1){
                    $imgThumb = wp_get_attachment_image_src($image->ID, 'full', false);
                    $url = $imgThumb[0];
                }
                $count++;
            }  
            if($echo){
                echo $url;
            }else{
                return $url;
            }

        }else{
            return false;
        }
    }


    static function getSliderImages($featured = true){
        global $post;
        // Set up Wordpress Database Query
        $args = array(
            'post_type'         => 'home_page_slider',
            'post_status'       => 'publish',
            'posts_per_page'    => -1,
            'caller_get_posts'  => 1
        );

        // Set Query Results to variable $q
        $q = new WP_Query($args);

        if($featured){
            // Loop thru and get the "Featured Image" of each Slider post
            if ($q->have_posts()){
                while ($q->have_posts()){
                    $q->the_post();
                    $src = wp_get_attachment_image_src(get_post_thumbnail_id($q->ID), 'slider');
                    $images[] = $src[0];                  
                }
            }
        }

        return $images;
    }
    
    /*
     *  Get Featured Image of post
     */
    static function getFeaturedImageSrc($id = null){
        if(is_null($id)){
            global $post;
            if(has_post_thumbnail($post->ID)){
                $img = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                return $img[0];
            }
            return false;
        }else{
            $img = wp_get_attachment_image_src(get_post_thumbnail_id($id), 'full');
            return $img[0];
        }
    }

    /*
     * Check if a certain sidebar is active
     */
    static function isSidebarActive($index) {
        global $wp_registered_sidebars;
        $widgetcolums = wp_get_sidebars_widgets();
        if ($widgetcolums[$index])
                return true;
        return false;
    }
    
    /*
     * Add Logo
     * 
     * @since 0.5.0
     */
    static function Logo($args = array()){

        $res = '';
        $class = '';
        if(isset($args['responsive']) && $args['responsive'] == 'true')
            $res = 'img-responsive';
        if(isset($args['class']))
            $class = ' '.$args['class'];
        
        if(!isset($args['logo']) || $args['logo'] === null){
            return '<a class="site-logo '.$class.'" href="'.get_bloginfo('url').'"><img class="'.$res.'" src="'.trailingslashit(THEME_IMAGES).'logo.png" /></a>';
        }else{
            return '<a class="site-logo '.$class.'" href="'.get_bloginfo('url').'"><img class="'.$res.'" src="'.trailingslashit(THEME_IMAGES).$logo.'" /></a>';
        }
    }
    
    /*
     * Add Link
     * 
     * @since 0.5.0
     */
    static function Link($args){
        if(is_array($args) && isset($args['href']) && isset($args['content'])){
            $link = '<a ';
            foreach($args as $key => $value){
                if($key !== 'content'){
                    $link .= "{$key}=\"{$value}\" ";
                }
            }
            $link .= ">{$args['content']}</a>";
            
            return $link;
        }else{
            return 'Missing $args["href"] or $args["content"]';
        }
    }    
    
    /*
     * Add Link
     * 
     * @since 0.5.0
     */
    static function Image($src = null, $args){
        if(!is_null($src)){
            $http = strpos($src, 'http://');
            if($http === false){
                $src = trailingslashit(THEME_IMAGES).$src;
            }
            $img = '<img src="'.$src.'" ';
            foreach($args as $key => $value){
                if($key !== 'content'){
                    $img .= "{$key}='{$value}' ";
                }
            }
            $img .= " />";
            
            return $img;
        }else{
            return 'Missing $src';
        }
    }
    
    /*
     * Add Link
     * 
     * @since 0.5.0
     */
    static function isChildOf($parent_slug){
        global $post;
        
        $parent_page = get_page($post->post_parent);
        
        if($parent_page->post_name == $parent_slug) return true;
        
        return false;
    }
    
    protected function loadSysInfo(){

    }
    

    /**
     * Utility::notice();
     * 
     * @since 0.9.0
     * @var string
     */

    
    static function pagination( $query=null ) {
 
        global $wp_query;
        $query = $query ? $query : $wp_query;
        $big = 999999999;

        $paginate = paginate_links(array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'type' => 'array',
            'total' => $query->max_num_pages,
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ));

        if ($query->max_num_pages > 1) :
      ?>
      <ul class="pagination">
        <?php
        foreach ( $paginate as $page ) {
          echo '<li>' . $page . '</li>';
        }
        ?>
      </ul>
    <?php
        endif;
    }
}
