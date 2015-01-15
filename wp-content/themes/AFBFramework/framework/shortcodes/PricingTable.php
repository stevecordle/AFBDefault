<?php if (! defined( 'ABSPATH' )) { header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!'); }
/**
 *
 */
if(!class_exists('TBPricing')):
    class TBPricing extends Shortcode
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
        static $name = 'TBPricing';
        
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
         * Render the shortcode
         * @since 0.1.0
         */
        static function pricing( $atts, $content = null ) {
            self::$add_script = false;
            
           
            $currencies = array(
                'US' => '$',
                'EU' => '€'
            );
            
            extract(shortcode_atts(array(
                "currency" => "US",
                "price" => '19.95',
                "title" => "Standard",
                "desc" => "An awesome Description",
                "link" => "#",
                "target" => "",
                "color" => "blue",
                "button" => "<i class='icon-check-sign icon-large'></i> Free Trial",
                "ribbon" => ""
            ), $atts));
            
            
            if($ribbon !== ''){
                $rc = explode('|', $ribbon);
                $ribbon_content = "<li class='ribbon-wrapper'>
                                       <div class='ribbon-{$rc[0]}'>{$rc[1]}</div>
                                   </li>";
            }else{
                $ribbon_content = '';
            }

            $content = self::contentHelper($content);
            $target = ( $target == 'new' ) ? ' target="_blank"' : '';
            $output = "<ul class='pricing-table {$color}'>
                          {$ribbon_content}
                          <li class='title'>{$title}</li>
                          <li class='price'>{$currencies[$currency]}{$price}</li>
                          <li class='description'>{$desc}</li>
                              <ul class='pricing-list'>
                                {$content}
                              </ul>
                          <li class='cta-button'><a class='btn btn-block btn-large btn-{$color}' href='{$link}' {$target}>{$button}</a></li>
                        </ul>";

            return $output;

        }
        
        static function item( $atts, $content = null){       
            $content = self::contentHelper($content);
            $out = "<li class='bullet-item'>{$content}</li>";
            return $out;
        }
        
        static function ribbon( $atts, $content = null){  
            extract(shortcode_atts(array(
                "color" => "green"
            ), $atts));
            
            $content = self::contentHelper($content);
            if(!isset($content)){
                $content = "Hot New Deal!";
            }
            if(!isset($color)){
                $color = 'green';
            }
            
            $out = "<li class='ribbon-wrapper'>
                       <div class='ribbon-{$color}'>{$content}</div>
                   </li>";
            return $out;
        }
        
            
    }
    
    add_shortcode('TBPricing', array( TBPricing::get_instance(), 'pricing' ));
    add_shortcode('TBPricingItem', array( TBPricing::get_instance(), 'item' ));
    add_shortcode('TBPricingRibbon', array( TBPricing::get_instance(), 'ribbon' ));
    
endif;