<?php if (! defined( 'ABSPATH' )) { header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!'); }
/**
 *
 */
if(!class_exists('TBMap')):
    class TBMap extends Shortcode{
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
        static $name = 'TBMap';
        
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
        
        static $markers = array();
        static $markerCount = 0;
        static $mapCount = 0;
        
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
            //add_action('wp_head', array($this , 'print_scripts'));
        }
        
        /**
         * Register scripts needed by shortcode
         * @since 0.1.0
         */
        static function register_scripts(){
            wp_register_script('GoogleMaps', 'http://maps.googleapis.com/maps/api/js?sensor=false');
            wp_register_script('gMapper', trailingslashit(FRAMEWORK_JS).'gmapper.jquery.js', array('jquery'), '1.0', true);
        }

        /**
         * Print scripts in footer
         * @since 0.1.0
         */
        static function print_scripts(){
            if(!self::$add_script)
                return;   
            wp_print_scripts('GoogleMaps');
            wp_print_scripts('gMapper');
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
        static function map( $atts, $content = null ) {
            self::$add_script = true;

            global $wp_query;

            extract(shortcode_atts(array(
                'width'     => '400',
                'height'    => '300',
                'zoom'      => '4',
                'type'      => 'ROADMAP',
                'controls'  => 'overview,type,pan,zoom,scale,rotate'
            ), $atts));

            $map_id = 'map_' . self::getID();
            $content = self::contentHelper($content);
            // Load google maps api 
            ob_start();
?>
		<div class="gmapper" data-mapid="<?php echo $map_id; ?>" data-type="<?php echo $type; ?>" data-zoom="<?php echo $zoom; ?>" data-width="<?php echo $width; ?>" data-height="<?php echo $height; ?>" data-controls="<?php echo $controls; ?>">
                    <div id="<?php echo $map_id; ?>"></div>
                    <?php foreach(self::$markers as $marker): ?>
                        <span class="marker" data-address="<?php echo $marker['address']; ?>" <?php echo ($marker['center'] == 'true')? 'data-center="true"' : ''; ?>>
                            <?php echo $marker['content']; ?>
                        </span>
                    <?php endforeach; ?>
		</div>                     
<?php  
            $output = ob_get_contents();
            ob_end_clean();
            return "<!--TBMap:map_id:{$map_id}-->" . $output . "<!--end TBMap: -->";
        }
        
        static function marker( $atts, $content = null){                 
            
            extract(shortcode_atts(array(
                "address" => "19000 Osmus Livonia Mi 48512",
                "center" => "false"
            ), $atts));
            
            $content = self::contentHelper($content);

            self::$markers[self::$markerCount] = array('address' => $address, 'content' => $content, 'center' => 'false', 'id' => self::$markerCount.'m'.self::$mapCount);
            self::$markerCount++;

        }
    }
    
    add_shortcode('TBMap', array( TBMap::get_instance(), 'map' ));
    add_shortcode('TBMarker', array( TBMap::get_instance(), 'marker' ));
    
endif;

//
///**
// *
// */
//if(!class_exists('TBMap')):
//    class TBMap extends Shortcode
//    {
//        /**
//         * Shortcode ID
//         * @since 0.1.0
//         * @var int
//         */
//        static $shortcode_id = 1;
//        
//        /**
//         * Shortcode Name
//         * @since 0.1.0
//         * @var string
//         */
//        static $name = 'TBMap';
//        
//        /**
//         * Add required script/s for shortcode
//         * @since 0.1.0
//         * @var bool
//         */
//        static $add_script = false;
//        
//        /**
//         * Instance of class
//         * @since 0.1.0
//         * @var object
//         */
//        protected static $instance = NULL;
//        
//        static $markers = array();
//        
//        static $markerCount = 0;
//
//        static $mapCount = 0;
//
//        /**
//         * Get instance of class
//         * @since 0.1.0
//         */
//        public static function get_instance()
//        {
//            NULL === self::$instance and self::$instance = new self;
//            return self::$instance; // return the object
//        }
//        
//        /**
//         * Class Constructor
//         * @since 0.1.0
//         */
//        function __construct(){
//            //Register scripts before the wp_head() action
//            add_action('init', array($this , 'register_scripts'));
//
//            //Print scripts in the footer
//            add_action('wp_footer', array($this , 'print_scripts'));
//        }
//        
//        /**
//         * Register scripts needed by shortcode
//         * @since 0.1.0
//         */
//        static function register_scripts(){
//        }
//
//        /**
//         * Print scripts in footer
//         * @since 0.1.0
//         */
//        static function print_scripts(){
//            if(!self::$add_script)
//                return;   
//        }
//        
//        /**
//         * Increment ID of shortcode for multiple use
//         * @since 0.1.0
//         */
//        static function getID(){
//            return self::$shortcode_id++;
//        }
//        
//        /**
//         * Render the shortcode
//         * @since 0.1.0
//         */
//        static function map( $atts, $content = null ) {
//            self::$add_script = true;
//            
//            extract(shortcode_atts(array(
//                'width'     => '400',
//                'height'    => '300',
//                'zoom'      => '4',
//                'type'      => 'ROADMAP',
//                'controls'  => 'overview,type,pan,zoom,scale,rotate'
//            ), $atts));
//
//            $content = self::contentHelper($content);
//            $output = '';            
//            if(is_array(self::$tabs)){
//                $active = "active";
//                foreach(self::$tabs as $tab){
//                    $tab_items[] = "<li class='{$active}'><a href='#tab".$tab['id']."' data-toggle='tab'>".$tab['title']."</a></li>";
//                    $panes[] = "<div class='tab-pane {$active}' id='tab".$tab['id']."'>".$tab['content']."</div>";
//                    $active = '';
//                }
//                $output .= "\n". "<ul class='nav nav-tabs' id='tabGroup".self::$tabGroupCount."'>".implode("\n", $tab_items)."</ul>";
//                $output .= "\n". "<div class='tab-content'>".implode("\n", $panes)."</div>";
//            }
//            self::$mapCount++;
//            self::$markerCount = 0;
//            return $output;
//
//        }
//        
//        static function marker( $atts, $content = null){                 
//            
//            extract(shortcode_atts(array(
//                "title" => "Tab Title",
//                "color" => "blue"
//            ), $atts));
//            
//            $content = self::contentHelper($content);
//
//            self::$tabs[self::$tabCount] = array('title' => $title, 'content' => $content, 'id' => self::$tabCount.'g'.self::$tabGroupCount);
//            self::$tabCount++;
//
//        }
//        
//            
//    }
//    
//    add_shortcode('TBMap', array( TBTabs::get_instance(), 'map' ));
//    add_shortcode('TBMarker', array( TBTabs::get_instance(), 'marker' ));
//    
//endif;