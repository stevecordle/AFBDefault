<?php if (! defined( 'ABSPATH' )) { header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!'); }

/**
 *
 */
if(!class_exists('Vars')):
    class Vars extends Shortcode
    {
        /**
         * Shortcode ID
         * @since 0.1.0
         * @var int
         */
        static $shortcode_id = 1;
        
        /**
         * Shortcode Name
         * @since 0.1.0
         * @var string
         */
        static $name = 'Vars';
        
        /**
         * Add required script/s for shortcode
         * @since 0.1.0
         * @var bool
         */
        static $add_script = false;
        
        /**
         * Instance of class
         * @since 0.1.0
         * @var object
         */
        protected static $instance = NULL;
        
        /**
         * Get instance of class
         * @since 0.1.0
         */
        public static function get_instance()
        {
            NULL === self::$instance and self::$instance = new self;
            return self::$instance; // return the object
        }
        
        /**
         * Class Constructor
         * @since 0.1.0
         */
        function __construct(){

            //Register scripts before the wp_head() action
            add_action('init', array($this , 'register_scripts'));

            //Print scripts in the footer
            add_action('wp_footer', array($this , 'print_scripts'));
        }
        
        /**
         * Register scripts needed by shortcode
         * @since 0.1.0
         */
        static function register_scripts(){
        }

        /**
         * Print scripts in footer
         * @since 0.1.0
         */
        static function print_scripts(){
            if(!self::$add_script)
                return;   
        }
        
        /**
         * Increment ID of shortcode for multiple use
         * @since 0.1.0
         */
        static function getID(){
            return self::$shortcode_id++;
        }
        
        /**
         * Return the the Variable of your choosing
         * @since 0.1.0
         */
        static function vars( $atts, $content = null ) {
            self::$add_script = false;
            
            shortcode_atts(array(
                'url' => 'site',
                'post' => 'name',
                'post_id' => ''
            ), $atts);
            
            if(isset($atts['url'])){
                switch($atts['url']){
                    case 'site':
                        return site_url();
                        break;
                    case 'template':
                        return get_template_directory_uri();
                        break;
                    case 'register':
                        return wp_registration_url();
                        break;
                    case 'login':
                        return wp_login_url();
                        break;
                    case 'logout':
                        return wp_logout_url();
                        break;
                    case 'wp_version':
                        return get_bloginfo('version');
                        break;
                    default:
                        return site_url();
                }
            }
            if(isset($atts['post'])){
                if(isset($atts['post_id'])){
                    $post = get_post($atts['post_id']);
                }else{
                    global $post;
                }
                switch($atts['post']){
                    case 'id':              return $post->ID;
                    case 'slug':            return $post->post_name;
                    case 'author':          return $post->post_author;
                    case 'name':            return $post->post_name;
                    case 'type':            return $post->post_type;
                    case 'title':           return $post->post_title;
                    case 'date':            return $post->post_date;
                    case 'date_gmt':        return $post->post_date_gmt;
                    case 'content':         return $post->post_content;
                    case 'excerpt':         return $post->post_excerpt;
                    case 'status':          return $post->post_status;
                    case 'parent':          return $post->post_parent;
                    case 'modified':        return $post->post_modified;
                    case 'comment_count':   return $post->post_comment_count;
                    case 'parent':          return $post->post_parent;
                    default:                return $post->post_title;
                }
                return;
            }
        }
        
    }
    
    add_shortcode('Vars', array( Vars::get_instance(), 'vars' ));
    
endif;