<?php if (! defined( 'ABSPATH' )) { header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!'); }

/**
 *
 */
if(!class_exists('TBGrid')):
    class TBGrid extends Shortcode
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
        static $name = 'TBColumns';
        
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
        
        public $columnMap = array();

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
         * Render the "ROW" shortcode
         * @since 0.1.0
         */
        static function row( $atts, $content = null ) {
            self::$add_script = false;
            
            extract(shortcode_atts(array(), $atts));
                        
            // Sanitize and execute shortcodes
            $content = self::contentHelper($content);
            
            $output = "<div class='row'>";   
            $output .= $content;
            $output .= '</div>';
            
            return $output;

        }
        
        /**
         * Render the "COLUMN shortcode
         * @since 0.1.0
         */
        static function column( $atts, $content = null){   
            self::$add_script = false;

            extract(shortcode_atts(array(
                'xs' => 12,
                'sm' => 12,
                'md' => 12,
                'lg' => 12,
                'xs_offset' => 0,
                'sm_offset' => 0,
                'md_offset' => 0,
                'lg_offset' => 0
            ), $atts, 'column'));
            
            $offset = '';
            $span = '';
            foreach($atts as $att => $val){
                if(strpos($att, 'offset')){
                    $offset .= 'col-'.str_replace('_', '-',$att).'-'.$val.' ';
                }else{
                    $span .= 'col-'.$att.'-'.$val.' ';
                }
            }
            
            
            $content = self::contentHelper($content, 'C');
            $output = "<div class='{$span} {$offset}'>"; 
            $output .= $content;
            $output .= '</div>';
            
            return $output;
            
        }
        
    }
    
    add_shortcode('Row', array( TBGrid::get_instance(), 'row' ));
    add_shortcode('Column', array( TBGrid::get_instance(), 'column' ));
    
endif;