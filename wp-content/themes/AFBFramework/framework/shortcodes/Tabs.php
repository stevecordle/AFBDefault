<?php if (! defined( 'ABSPATH' )) { header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!'); }
/**
 *
 */
if(!class_exists('TBTabs')):
    class TBTabs extends Shortcode
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
        static $name = 'TBTabs';
        
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
        
        static $tabs = array();
        
        static $tabCount = 0;

        static $tabGroupCount = 0;

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
         * Render the shortcode
         * @since 0.1.0
         */
        static function tabGroup( $atts, $content = null ) {
            self::$add_script = true;
            
            extract(shortcode_atts(array(
   
            ), $atts));

            $content = self::contentHelper($content);
            $output = '';            
            if(is_array(self::$tabs)){
                $active = "active";
                foreach(self::$tabs as $tab){
                    $tab_items[] = "<li class='{$active}'><a href='#tab".$tab['id']."' data-toggle='tab'>".$tab['title']."</a></li>";
                    $panes[] = "<div class='tab-pane {$active}' id='tab".$tab['id']."'>".$tab['content']."</div>";
                    $active = '';
                }
                $output .= "\n". "<ul class='nav nav-tabs' id='tabGroup".self::$tabGroupCount."'>".implode("\n", $tab_items)."</ul>";
                $output .= "\n". "<div class='tab-content'>".implode("\n", $panes)."</div>";
            }
            self::$tabGroupCount++;
            self::$tabCount = 0;
            return $output;

        }
        
        static function tab( $atts, $content = null){                 
            
            extract(shortcode_atts(array(
                "title" => "Tab Title",
                "color" => "blue"
            ), $atts));
            
            $content = self::contentHelper($content);

            self::$tabs[self::$tabCount] = array('title' => $title, 'content' => $content, 'id' => self::$tabCount.'g'.self::$tabGroupCount);
            self::$tabCount++;

        }
        
            
    }
    
    add_shortcode('TBTabGroup', array( TBTabs::get_instance(), 'tabGroup' ));
    add_shortcode('TBTab', array( TBTabs::get_instance(), 'tab' ));
    
endif;