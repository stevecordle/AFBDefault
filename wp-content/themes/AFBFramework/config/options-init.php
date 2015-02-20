<?php if(!defined('ABSPATH')) die; 

if (!class_exists('Options_Redux_Framework_config')) {

    class Options_Redux_Framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();
            
            // Set the default arguments
            $this->setArguments();
            
            // Set a few help tabs so you can see how it's done
            //$this->setHelpTabs();
            
            // Create the sections and fields
            $this->setSections();
            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            };
            
            //Add FontAwesome to the Options Panel
            add_action( 'redux/page/'.$this->args['opt_name'].'/enqueue', array( $this, 'loadFA' ) );

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }
        
        function loadFA() {
            // Uncomment this to remove elusive icon from the panel completely
            //wp_deregister_style( 'redux-elusive-icon' );
            //wp_deregister_style( 'redux-elusive-icon-ie7' );

            wp_register_style(
                'redux-font-awesome',
                '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css',
                array(),
                time(),
                'all'
            );  
            wp_enqueue_style( 'redux-font-awesome' );
        }

        public function setSections() {
            
            $this->sections[] = array(
                'icon'      => 'el-icon-wordpress',
                'title'     => __('Website Branding', 'TBOptions'),
                'heading'   => __('Website Branding Settings', 'TBOptions'),
                'desc'      => __('This section provides options to brand the theme to your company/website.', 'TBOptions'),
                'fields'    => array(
                    array(
                        'id'        => 'logo',
                        'type'      => 'media',
                        'url'       => true,
                        'preview'   => true,
                        'title'     => __('Upload your logo', 'TBOptions'),
                        'desc'      => __('Upload the logo for your company/website', 'TBOptions'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'TBOptions'),
                        'default'   => array('url'   =>  trailingslashit(FRAMEWORK_ASSETS).'img/logo.png') 
                    )
                )
            );
            
            $this->sections[] = array(
                'title' => __('Sliders', 'TBOptions'),
                'desc'  => __('Homepage Sliders', 'TBOptions'),
                'icon'  => 'el-icon-picture',
                'fields' => array(
                    array(
                        'id'        => 'opt-slides',
                        'type'      => 'slides',
                        'title'     => __('Sliders', 'TBOptions'),
                        'subtitle'  => __('Add sliders for homepage slider section.', 'TBOptions'),
                        'placeholder'   => array(
                            'title'         => __('This is a title', 'TBOptions'),
                            'description'   => __('Description Here', 'TBOptions'),
                            'url'           => __('Give us a link!', 'TBOptions'),
                        )
                    )
                )
            );

            $this->sections[] = array(
                'type' => 'divide',
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'TBOptions'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }

        }

        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array (
                'opt_name' => 'Ops',
                'dev_mode' => (WP_ENV === 'dev')?'1':'0',
                'allow_sub_menu' => '1',
                'async_typography' => '1',
                'customizer' => '1',
                'default_mark' => '*',
                'hint-icon' => 'el-icon-question-sign',
                'icon_position' => 'right',
                'icon_color' => '#548dbf',
                'icon_size' => 'large',
                'tip_style_color' => 'light',
                'tip_style_shadow' => '1',
                'tip_position_my' => 'top left',
                'tip_position_at' => 'bottom right',
                'tip_show_duration' => '500',
                'tip_show_event' => 
                array (
                  0 => 'mouseover',
                  1 => 'click',
                ),
                'tip_hide_effect' => 'fade',
                'tip_hide_duration' => '500',
                'tip_hide_event' => 
                array (
                  0 => 'mouseleave',
                  1 => 'unfocus',
                ),
                'intro_text' => '',
                'menu_title' => 'Theme Options',
                'menu_type' => 'menu',
                'output' => '1',
                'output_tag' => '1',
                'page_icon' => 'icon-themes',
                'page_parent' => 'themes.php',
                'page_parent_post_type' => 'your_post_type',
                'page_permissions' => 'manage_options',
                'page_slug' => '_options',
                'page_title' => 'Theme Options',
                'save_defaults' => '1',
                'show_import_export' => '1',
                'update_notice' => false,
                'footer_credit' => '&copy;'.date('Y').' Alliance Franchise Brands'
            );

            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args["display_name"] = $theme->get("Name");
            $this->args["display_version"] = $theme->get("Version");

            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/AllianceFranchiseBrands',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.linkedin.com/company/3151837',
                'title' => 'Find us on LinkedIn',
                'icon'  => 'el-icon-linkedin'
            );

        }

    }
    
    global $reduxConfig;
    $reduxConfig = new Options_Redux_Framework_config();
}