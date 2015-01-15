<?php if(!defined('ABSPATH')) die; 

if (!class_exists('TBOptions_Redux_Framework_config')) {

    class TBOptions_Redux_Framework_config {

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
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));
            
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
        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {

            foreach($options as $option => $value){
                switch($option){
                    case 'opt-ace-editor-css':
                        
                        require_once(trailingslashit(FRAMEWORK_CLASSES_DIR).'lib/Less/Cache.php');
                        
                        //Set up needed variables
                        global $wp_filesystem;
                        $themeCSSFile = trailingslashit(THEME_CSS_DIR).'style.css';
                        $themeLESSFile = trailingslashit(THEME_CSS_DIR).'style.less';
                        $customLESSFile = trailingslashit(FRAMEWORK_CSS_DIR).'custom-style.less';
                        
                        //Instantiate lessc class, and set up Import Directories
                        $ops = array('compress'=>true);
                        $less = new Less_Parser($ops);
                                                
                        //if $wp_filesystem isn't available, include file.php from Wordpress Core
                        if( empty( $wp_filesystem ) ) {
                            require_once( ABSPATH .'/wp-admin/includes/file.php' );
                            WP_Filesystem();
                        }
                        
                        //Save the Options Panel Custom CSS into the $customLESSFile file.
                        if( $wp_filesystem ) {
                            $wp_filesystem->put_contents(
                                $customLESSFile,
                                $value,
                                FS_CHMOD_FILE // predefined mode settings for WP files
                            );
                                                    
                            //Compile and Cache LESS Code
                            try{
                                Less_Cache::$cache_dir = trailingslashit(FRAMEWORK_CSS_CACHE_DIR);
                                $files = array(
                                    $themeLESSFile => trailingslashit(THEME_CSS_DIR),
                                    $customLESSFile => trailingslashit(FRAMEWORK_CSS_DIR)
                                );
                                
                                $css_file = Less_Cache::Get($files, array('compress' => true));
                                
                                $css = $wp_filesystem->get_contents(trailingslashit(FRAMEWORK_CSS_CACHE_DIR).$css_file);
                                                                
                                $wp_filesystem->put_contents(
                                    $themeCSSFile,
                                    $css,
                                    FS_CHMOD_FILE
                                );
                                Utility::notice('updated', 'LESS Compiler Success!', "Style.css has been compiled and minified.<br/>CacheFileName: {$css_file}");
                                break;
                            } catch (Exception $ex) {
                                $ex_message = $ex->getMessage();
                                Utility::notice('error', 'LESS Compiler Error!', "<pre>{$ex_message}</pre>");
                                break;
                            }
                        }
                        break;
                    default:
                       
                }
            }
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'TBOptions'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'TBOptions'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {
            
            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'TBOptions'), $this->theme->display('Name'));
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>
                <?php $Utility = new Utility(); ?>
                <h3 style="margin:2px 0;border:none;"><?php echo $this->theme->display('Name'); ?></h3>
                    <ul class="theme-info" style="margin-top:4px;">
                        <li><?php printf(__('By %s', 'TBOptions'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'TBOptions'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'TBOptions') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS
//            $this->sections[] = array(
//                'title'     => __('Home Settings', 'TBOptions'),
//                'desc'      => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'TBOptions'),
//                'icon'      => 'el-icon-home',
//                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
//                'fields'    => array(
//
//                    array(
//                        'id'        => 'opt-web-fonts',
//                        'type'      => 'media',
//                        'title'     => __('Web Fonts', 'TBOptions'),
//                        'compiler'  => 'true',
//                        'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
//                        'desc'      => __('Basic media uploader with disabled URL input field.', 'TBOptions'),
//                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'TBOptions'),
//                        'hint'      => array(
//                            //'title'     => '',
//                            'content'   => 'This is a <b>hint</b> tool-tip for the webFonts field.<br/><br/>Add any HTML based text you like here.',
//                        )
//                    ),
//                    array(
//                        'id'        => 'section-media-start',
//                        'type'      => 'section',
//                        'title'     => __('Media Options', 'TBOptions'),
//                        'subtitle'  => __('With the "section" field you can create indent option sections.', 'TBOptions'),
//                        'indent'    => true // Indent all options below until the next 'section' option is set.
//                    ),
//                    array(
//                        'id'        => 'opt-media',
//                        'type'      => 'media',
//                        'url'       => true,
//                        'title'     => __('Media w/ URL', 'TBOptions'),
//                        'compiler'  => 'true',
//                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
//                        'desc'      => __('Basic media uploader with disabled URL input field.', 'TBOptions'),
//                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'TBOptions'),
//                        'default'   => array(),
//                        //'hint'      => array(
//                        //    'title'     => 'Hint Title',
//                        //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
//                        //)
//                    ),
//                    array(
//                        'id'        => 'section-media-end',
//                        'type'      => 'section',
//                        'indent'    => false // Indent all options below until the next 'section' option is set.
//                    ),
//                    array(
//                        'id'        => 'media-no-url',
//                        'type'      => 'media',
//                        'title'     => __('Media w/o URL', 'TBOptions'),
//                        'desc'      => __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'TBOptions'),
//                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'TBOptions'),
//                    ),
//                    array(
//                        'id'        => 'media-no-preview',
//                        'type'      => 'media',
//                        'preview'   => false,
//                        'title'     => __('Media No Preview', 'TBOptions'),
//                        'desc'      => __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'TBOptions'),
//                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'TBOptions'),
//                    ),
//                    array(
//                        'id'        => 'opt-gallery',
//                        'type'      => 'gallery',
//                        'title'     => __('Add/Edit Gallery', 'so-panels'),
//                        'subtitle'  => __('Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'so-panels'),
//                        'desc'      => __('This is the description field, again good for additional info.', 'TBOptions'),
//                    ),
//                    array(
//                        'id'            => 'opt-slider-label',
//                        'type'          => 'slider',
//                        'title'         => __('Slider Example 1', 'TBOptions'),
//                        'subtitle'      => __('This slider displays the value as a label.', 'TBOptions'),
//                        'desc'          => __('Slider description. Min: 1, max: 500, step: 1, default value: 250', 'TBOptions'),
//                        'default'       => 250,
//                        'min'           => 1,
//                        'step'          => 1,
//                        'max'           => 500,
//                        'display_value' => 'label'
//                    ),
//                    array(
//                        'id'            => 'opt-slider-text',
//                        'type'          => 'slider',
//                        'title'         => __('Slider Example 2 with Steps (5)', 'TBOptions'),
//                        'subtitle'      => __('This example displays the value in a text box', 'TBOptions'),
//                        'desc'          => __('Slider description. Min: 0, max: 300, step: 5, default value: 75', 'TBOptions'),
//                        'default'       => 75,
//                        'min'           => 0,
//                        'step'          => 5,
//                        'max'           => 300,
//                        'display_value' => 'text'
//                    ),
//                    array(
//                        'id'            => 'opt-slider-select',
//                        'type'          => 'slider',
//                        'title'         => __('Slider Example 3 with two sliders', 'TBOptions'),
//                        'subtitle'      => __('This example displays the values in select boxes', 'TBOptions'),
//                        'desc'          => __('Slider description. Min: 0, max: 500, step: 5, slider 1 default value: 100, slider 2 default value: 300', 'TBOptions'),
//                        'default'       => array(
//                            1 => 100,
//                            2 => 300,
//                        ),
//                        'min'           => 0,
//                        'step'          => 5,
//                        'max'           => '500',
//                        'display_value' => 'select',
//                        'handles'       => 2,
//                    ),
//                    array(
//                        'id'            => 'opt-slider-float',
//                        'type'          => 'slider',
//                        'title'         => __('Slider Example 4 with float values', 'TBOptions'),
//                        'subtitle'      => __('This example displays float values', 'TBOptions'),
//                        'desc'          => __('Slider description. Min: 0, max: 1, step: .1, default value: .5', 'TBOptions'),
//                        'default'       => .5,
//                        'min'           => 0,
//                        'step'          => .1,
//                        'max'           => 1,
//                        'resolution'    => 0.1,
//                        'display_value' => 'text'
//                    ),
//                    array(
//                        'id'        => 'opt-spinner',
//                        'type'      => 'spinner',
//                        'title'     => __('JQuery UI Spinner Example 1', 'TBOptions'),
//                        'desc'      => __('JQuery UI spinner description. Min:20, max: 100, step:20, default value: 40', 'TBOptions'),
//                        'default'   => '40',
//                        'min'       => '20',
//                        'step'      => '20',
//                        'max'       => '100',
//                    ),
//                    array(
//                        'id'        => 'switch-on',
//                        'type'      => 'switch',
//                        'title'     => __('Switch On', 'TBOptions'),
//                        'subtitle'  => __('Look, it\'s on!', 'TBOptions'),
//                        'default'   => true,
//                    ),
//                    array(
//                        'id'        => 'switch-off',
//                        'type'      => 'switch',
//                        'title'     => __('Switch Off', 'TBOptions'),
//                        'subtitle'  => __('Look, it\'s on!', 'TBOptions'),
//                        'default'   => false,
//                    ),
//                    array(
//                        'id'        => 'switch-custom',
//                        'type'      => 'switch',
//                        'title'     => __('Switch - Custom Titles', 'TBOptions'),
//                        'subtitle'  => __('Look, it\'s on! Also hidden child elements!', 'TBOptions'),
//                        'default'   => 0,
//                        'on'        => 'Enabled',
//                        'off'       => 'Disabled',
//                    ),
//                    array(
//                        'id'        => 'switch-fold',
//                        'type'      => 'switch',
//                        'required'  => array('switch-custom', '=', '1'),
//                        'title'     => __('Switch - With Hidden Items (NESTED!)', 'TBOptions'),
//                        'subtitle'  => __('Also called a "fold" parent.', 'TBOptions'),
//                        'desc'      => __('Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'TBOptions'),
//                        'default'   => false,
//                    ),
//                    array(
//                        'id'        => 'opt-patterns',
//                        'type'      => 'image_select',
//                        'tiles'     => true,
//                        'required'  => array('switch-fold', 'equals', '0'),
//                        'title'     => __('Images Option (with pattern=>true)', 'TBOptions'),
//                        'subtitle'  => __('Select a background pattern.', 'TBOptions'),
//                        'default'   => 0,
//                        'options'   => $sample_patterns
//                    ,
//                    ),
//                    array(
//                        'id'        => 'opt-homepage-layout',
//                        'type'      => 'sorter',
//                        'title'     => 'Layout Manager Advanced',
//                        'subtitle'  => 'You can add multiple drop areas or columns.',
//                        'compiler'  => 'true',
//                        'options'   => array(
//                            'enabled'   => array(
//                                'highlights'    => 'Highlights',
//                                'slider'        => 'Slider',
//                                'staticpage'    => 'Static Page',
//                                'services'      => 'Services'
//                            ),
//                            'disabled'  => array(
//                            ),
//                            'backup'    => array(
//                            ),
//                        ),
//                        'limits' => array(
//                            'disabled'  => 1,
//                            'backup'    => 2,
//                        ),
//                    ),
//                    
//                    array(
//                        'id'        => 'opt-homepage-layout-2',
//                        'type'      => 'sorter',
//                        'title'     => 'Homepage Layout Manager',
//                        'desc'      => 'Organize how you want the layout to appear on the homepage',
//                        'compiler'  => 'true',
//                        'options'   => array(
//                            'disabled'  => array(
//                                'highlights'    => 'Highlights',
//                                'slider'        => 'Slider',
//                            ),
//                            'enabled'   => array(
//                                'staticpage'    => 'Static Page',
//                                'services'      => 'Services'
//                            ),
//                        ),
//                    ),
//                    array(
//                        'id'        => 'opt-slides',
//                        'type'      => 'slides',
//                        'title'     => __('Slides Options', 'TBOptions'),
//                        'subtitle'  => __('Unlimited slides with drag and drop sortings.', 'TBOptions'),
//                        'desc'      => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', 'TBOptions'),
//                        'placeholder'   => array(
//                            'title'         => __('This is a title', 'TBOptions'),
//                            'description'   => __('Description Here', 'TBOptions'),
//                            'url'           => __('Give us a link!', 'TBOptions'),
//                        ),
//                    ),
//                    array(
//                        'id'        => 'opt-presets',
//                        'type'      => 'image_select',
//                        'presets'   => true,
//                        'title'     => __('Preset', 'TBOptions'),
//                        'subtitle'  => __('This allows you to set a json string or array to override multiple preferences in your theme.', 'TBOptions'),
//                        'default'   => 0,
//                        'desc'      => __('This allows you to set a json string or array to override multiple preferences in your theme.', 'TBOptions'),
//                        'options'   => array(
//                            '1'         => array('alt' => 'Preset 1', 'img' => ReduxFramework::$_url . '../sample/presets/preset1.png', 'presets' => array('switch-on' => 1, 'switch-off' => 1, 'switch-custom' => 1)),
//                            '2'         => array('alt' => 'Preset 2', 'img' => ReduxFramework::$_url . '../sample/presets/preset2.png', 'presets' => '{"slider1":"1", "slider2":"0", "switch-on":"0"}'),
//                        ),
//                    ),
//                    array(
//                        'id'            => 'opt-typography',
//                        'type'          => 'typography',
//                        'title'         => __('Typography', 'TBOptions'),
//                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
//                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
//                        'font-backup'   => true,    // Select a backup non-google font in addition to a google font
//                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
//                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
//                        //'font-size'     => false,
//                        //'line-height'   => false,
//                        //'word-spacing'  => true,  // Defaults to false
//                        //'letter-spacing'=> true,  // Defaults to false
//                        //'color'         => false,
//                        //'preview'       => false, // Disable the previewer
//                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
//                        'output'        => array('h2.site-description'), // An array of CSS selectors to apply this font style to dynamically
//                        'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
//                        'units'         => 'px', // Defaults to px
//                        'subtitle'      => __('Typography option with each property can be called individually.', 'TBOptions'),
//                        'default'       => array(
//                            'color'         => '#333',
//                            'font-style'    => '700',
//                            'font-family'   => 'Abel',
//                            'google'        => true,
//                            'font-size'     => '33px',
//                            'line-height'   => '40px'),
//                        'preview' => array('text' => 'ooga booga'),
//                    ),
//                ),
//            );
//
//            $this->sections[] = array(
//                'type' => 'divide',
//            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-wordpress',
                'title'     => __('Website Branding', 'TBOptions'),
                'heading'   => __('Website Branding Settings', 'TBOptions'),
                'desc'      => __('This section provides options to brand the theme to your company/website.', 'TBOptions'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-branding-logo',
                        'type'      => 'media',
                        'url'       => true,
                        'preview'   => true,
                        'title'     => __('Upload your logo', 'TBOptions'),
                        'desc'      => __('Upload the logo for your company/website', 'TBOptions'),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', 'TBOptions'),
                        'default'   => array('url'   =>  trailingslashit(FRAMEWORK_ASSETS).'img/logo.png') 
                    ),
                    array(
                        'id'            => 'opt-branding-company-name',
                        'type'          => 'text',
                        'title'         => __('Company Name', 'TBOptions'),
                        'desc'          => __('Add your copyright info.', 'TBOptions'),
                        'placeholder'   => 'Company Name',
                        'default'       => 'Company Name'
                    ),
                    array(
                        'id'            => 'opt-branding-copyright',
                        'type'          => 'text',
                        'title'         => __('Copyright Info', 'TBOptions'),
                        'desc'          => __('Add your copyright info.', 'TBOptions'),
                        'placeholder'   => __('&copy;'.date('Y').' Company Name LLC.', 'TBOptions'),
                        'default'       => __('&copy;'.date('Y').' Company Name LLC.', 'TBOptions')
                    ),
                    array(
                        'id'       => 'opt-branding-social-links',
                        'type'     => 'button_set',
                        'title'    => __('Social Media Links', 'TBOptions'),
                        'subtitle' => __('Select the social media links that you would like to include.', 'TBOptions'),
                        'multi'    => true,
                        'options' => array(
                            'facebook'  => 'Facebook', 
                            'twitter'   => 'Twitter', 
                            'linkedin'  => 'LinkedIn'
                         ), 
                        'default' => array()
                    ),
                    array(
                        'id'        => 'opt-branding-social-facebook',
                        'type'      => 'text',
                        'required'  => array('opt-branding-social-links', 'in_array', 'facebook'),
                        'title'     => __('Facebook Link', 'TBOptions'),
                        'desc'      => __('Add your Facebook link.', 'TBOptions'),
                        'placeholder'   => 'http://www.facebook.com/myCompany'
                    ),
                    array(
                        'id'        => 'opt-branding-social-twitter',
                        'type'      => 'text',
                        'required'  => array('opt-branding-social-links', 'in_array', 'twitter'),
                        'title'     => __('Twitter Link', 'TBOptions'),
                        'desc'      => __('Add your Twitter link.', 'TBOptions'),
                        'placeholder'   => 'http://www.twitter.com/myCompany'
                    ),
                    array(
                        'id'        => 'opt-branding-social-linkedin',
                        'type'      => 'text',
                        'required'  => array('opt-branding-social-links', 'in_array', 'linkedin'),
                        'title'     => __('LinkedIN Link', 'TBOptions'),
                        'desc'      => __('Add your LinkedIn link.', 'TBOptions'),
                        'placeholder'   => 'http://www.linkedin.com/myCompany'
                    )
                )
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Styling/Scripting', 'TBOptions'),
                'heading'   => __('Website Style & Script Settings'),
                'desc'      => __('This section provides options to add/modify styles & scripts.', 'TBOptions'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-ace-editor-css',
                        'type'      => 'ace_editor',
                        'title'     => __('Paste your LESS/CSS Code below.', 'TBOptions'),
                        'subtitle'  => __('Note that the code below will be compiled using <a href="http://lessphp.gpeasy.com" target="_blank">Less.php</a>.  Depending on the size of your LESS/CSS Code, it could take some time to compile after saving your changes.', 'TBOptions'),
                        'mode'      => 'less',
                        'compiler'  => true,
                        'theme'     => 'monokai',
                        'desc'      => '*If there is a compiler error, you will see an admin notice explaining the error.',
                        'default'   => "#logo{\n\tdisplay:block;\n\t.border-radius(0);\n}"
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
                'title'     => __('Import / Export', 'TBOptions'),
                'desc'      => __('Import and Export your options, text or URL.', 'TBOptions'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', 'TBOptions'),
                'desc'      => __($this->theme->get('Description'), 'TBOptions'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'TBOptions'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }

        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'TBOptions'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'TBOptions')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'TBOptions'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'TBOptions')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'TBOptions');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array (
                'opt_name' => 'TBOptions',
                'dev_mode' => '1',
                'allow_sub_menu' => '1',
                'async_typography' => '1',
                'customizer' => '1',
                'default_mark' => '*',
                'footer_text' => '<p>&copy;'.date('Y').' ThemeBrew LLC',
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
    $reduxConfig = new TBOptions_Redux_Framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('TBOptions_my_custom_field')):
    function TBOptions_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('TBOptions_validate_callback_function')):
    function TBOptions_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
