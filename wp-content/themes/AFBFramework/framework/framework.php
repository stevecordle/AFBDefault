<?php if (!defined( 'ABSPATH' )){ header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!');}
/**
 * Themebrew Framework.
 *
 * @package   ThemeBrew
 * @author    Steve Cordle <steve@devinate.com>
 * @license   GPL-2.0+
 * @link      http://themebrew.com
 * @copyright 2015 Themebrew, LLC
 */

class ThemeBrew {

    /**
     * Framework version
     * 
     * @since 0.1.0
     * @var string
     */
    protected $version = '0.7';
    
    /**
     * Framework Slug
     * 
     * @since 0.1.0
     * @var string
     */
    protected $framework_slug = 'themebrew';
    
    /**
     * Get theme info
     * 
     * @since 0.1.0
     * @var array
     */
    protected $themeData = array();  
    
    /**
     * Get server info
     * 
     * @since 0.1.0
     * @var array
     */
    protected $systemInfo = array();
    
    /**
     * Instance of framework class
     * 
     * @since 0.1.0
     * @var object
     */
    protected static $instance = null;
    
    protected static $lorem_ipsum = 
"<p>Lorem ipsum dolor sit amet, et fierent urbanitas vim, pri at magna consetetur, essent recusabo eu pri. Te pro nulla virtute civibus, id illum altera mentitum eam, cum et atomorum mandamus. Solum sententiae et pri, pri expetendis temporibus ea. Justo dicat legimus ex his, dolor timeam perfecto cu duo. Ad duis prima moderatius pro, possim veritus nam id. Vel at possit delicatissimi, ut lucilius mediocrem explicari sit.<p>Id vim mazim vitae eruditi, ius ex adipiscing constituam appellantur, qui at aliquid debitis prodesset. Qui summo novum utamur ne, eam probo aeterno cu. Persecuti definitionem nam ea, graecis tacimates mea id, est graeco alienum id. Cum ut magna intellegat, graeco gubergren hendrerit no per. Eu sea iudico vocent labores.</p>
<p>Id vim mazim vitae eruditi, ius ex adipiscing constituam appellantur, qui at aliquid debitis prodesset. Qui summo novum utamur ne, eam probo aeterno cu. Persecuti definitionem nam ea, graecis tacimates mea id, est graeco alienum id. Cum ut magna intellegat, graeco gubergren hendrerit no per. Eu sea iudico vocent labores.</p>
<p>Ad justo porro invidunt per, vel ne everti eripuit nonumes. An gubergren constituam efficiantur usu, ridens copiosae sententiae ex vel. At sumo blandit euripidis sed, aeque pertinacia no ius. Essent sensibus qui an. At ius omnium virtute delectus, regione inimicus interesset ei eam, ex eam epicuri blandit.</p>";
    
     
    /**
     * Shortcodes active/inactive list array
     * 
     * @since 0.1.0
     * @var array
     */
    public $shortcodes = array();
         
    /**
     * Plugins active/inactive list array
     * 
     * @since 0.1.0
     * @var array
     */
    
    public $google_fonts = array();

    public $plugins = array();

    public $theme_features = array();
    
    public $config = array();
    
    public $features = array();
    
    public $menus = array();
    
    public $widgets = array();
    /**
     * Initialize framework
     * 
     * @since 0.1.0
     */
    private function __construct(){
        
        $this->themeData = wp_get_theme();
        self::loadConstants();
        self::loadConfig();
        self::themeSupport();
        self::registerMenus();
        self::loadClasses();
        self::loadCustomPosts();
        self::loadWidgetAreas();
        self::loadWidgets();
        self::loadOptions();
        self::init();
        self::loadShortcodes();
        self::loadHooks(); 
    }   
    
    protected function loadConfig(){
        //Check if theme/config/framework.ini exists and load that config file.
        if(file_exists(trailingslashit(THEME_DIR).'config/framework.ini')){
            $arr = parse_ini_file(trailingslashit(THEME_DIR).'config/framework.ini', true);
        }else{  // Otherwise load the default framework.ini config file.
            if(file_exists(trailingslashit(FRAMEWORK_DIR).'config/framework.ini')){
                $arr = parse_ini_file(trailingslashit(FRAMEWORK_DIR).'config/framework.ini', true);
            }    
        }
        if(is_array($arr)){
            $this->config = $arr;
        }
        return;
    }    
    
    /**
     * Added for convenience of a file that loads automatically to place hooks in
     * 
     * @since 0.1.0
     */
    protected function loadHooks(){
        //Set some default hooks here
        
        add_action('after_setup_theme', array($this, 'changeLoginLogo'));
        add_filter('login_headerurl', array($this, 'changeLoginURL'));
        add_filter('login_headertitffle', array($this, 'changeLoginTitle'));
        add_action('login_enqueue_scripts', array($this, 'changeLoginLogo'));
        //Check if theme/config/hooks.php exists and load that hooks file.
        if(file_exists(trailingslashit(THEME_DIR).'config/hooks.php')){
            require_once trailingslashit(THEME_DIR).'config/hooks.php';
        }else{
            if(file_exists(trailingslashit(FRAMEWORK_DIR).'config/hooks.php')){
                require_once trailingslashit(FRAMEWORK_DIR).'config/hooks.php';
            }  
        }
        return;
    }
    
    function changeLoginLogo(){
        global $Ops;
        $path = str_replace('wordpress', '', getcwd());
        $parsed = parse_url($Ops['logo']['url']);
        $logo = $path.$parsed['path'];
        list($width, $height) = getimagesize($logo);
?>
        <style type="text/css">
            body.login div#login h1 a {
                background-image: url(<?php echo $Ops['logo']['url']; ?>);
                width: <?php echo $width; ?>px;
                height: <?php echo $height; ?>px;
                background-size: <?php echo $width.'px '.$height; ?>px;
            }
        </style>
<?php
    }
    
    function changeLoginURL(){
        return SITEURL;
    }
    
    function changeLoginTitle(){
        return get_bloginfo('name');
    }
        
    /**
     * Add theme support
     * 
     * @since 0.1.0
     */
    function themeSupport(){
        if(function_exists('add_theme_support') && isset($this->config['theme_features']['features'][0])){
            foreach($this->config['theme_features']['features'] as $feature){
                $this->features[] = $feature;
                add_theme_support($feature);
            }
        }
    }  
    
    function registerMenus(){
        $menus = array();
        if(function_exists('add_theme_support') && isset($this->config['menu_positions']['positions'][0])){
            foreach($this->config['menu_positions']['positions'] as $position => $value){
                if(count($this->config['menu_positions']['positions']) >= 1){
                    $id = self::slugify($value);
                    $menus[$id] = $value;
                    $this->menus[] = $value;
                }
            }
        }

        if(!empty($menus)){
            if(function_exists('register_nav_menus')):
                register_nav_menus($menus);
            endif;
            return true;
        }else{
            return false;
        }
    }
    
    function loadOptions(){
        require_once(trailingslashit(FRAMEWORK_ADMIN_DIR).'admin-init.php');
    }
    
    /**
     * Load CONSTANT variables
     * 
     * @since 0.1.0
     */
    protected function loadConstants(){
        
        define('WP_VERSION',            get_bloginfo('version'));
        define('THEME_NAME',            $this->themeData->get('Name'));
        define('THEME_SLUG',            $this->themeData->get('Template'));
        define('THEME_VERSION',         $this->themeData->get('Version'));
        define('THEME_URL',             get_template_directory_uri());
        define('THEME_AUTHOR',          $this->themeData->get('Author'));
        define('THEME_AUTHOR_URL',      $this->themeData->get('AuthorURI'));
        define('THEME_TEXT_DOMAIN',     $this->themeData->get('TextDomain'));
        define('THEME_DIR',             get_template_directory());
        define('SITEURL',               get_site_url());
        
        define('THEME_ASSETS',          trailingslashit(THEME_URL) . 'assets');
        define('THEME_ASSETS_DIR',      trailingslashit(THEME_DIR) . 'assets');
        define('THEME_IMAGES',          trailingslashit(THEME_ASSETS) . 'img');
        define('THEME_IMAGES_DIR',      trailingslashit(THEME_ASSETS_DIR) . 'img');
        define('THEME_JS',              trailingslashit(THEME_ASSETS) . 'js');
        define('THEME_JS_DIR',          trailingslashit(THEME_ASSETS_DIR) . 'js');
        define('THEME_CSS',             trailingslashit(THEME_ASSETS) . 'css');
        define('THEME_CSS_DIR',         trailingslashit(THEME_ASSETS_DIR) . 'css');
        
        define('CONFIG_DIR',            trailingslashit(THEME_DIR) . 'config');
        define('CONFIG_URL',            trailingslashit(THEME_URL) . 'config');        /*
         * ThemeBrew (Framework) Specific Constants
         */
        define('FRAMEWORK_NAME',            "Theme Brew");
        define('FRAMEWORK_SLUG',            "themebrew");
        define('FRAMEWORK_VERSION',         '0.1.0');
        define('FRAMEWORK_SITE',            'http://www.themebrew.com');
        define('FRAMEWORK_DOCS',            'http://www.themebrew.com/docs');
        define('FRAMEWORK_SUPPORT',         'http://www.themebrew.com/support');
        define('FRAMEWORK_DIR',             trailingslashit(THEME_DIR).'framework');
        define('FRAMEWORK_URL',             trailingslashit(THEME_URL).'framework');
        
        define('FRAMEWORK_CLASSES',         trailingslashit(FRAMEWORK_URL).'classes');
        define('FRAMEWORK_CLASSES_DIR',     trailingslashit(FRAMEWORK_DIR).'classes');
        
        define('FRAMEWORK_SHORTCODES',      trailingslashit(FRAMEWORK_URL).'shortcodes');
        define('FRAMEWORK_SHORTCODES_DIR',  trailingslashit(FRAMEWORK_DIR).'shortcodes');
        
        define('FRAMEWORK_PLUGINS',         trailingslashit(FRAMEWORK_URL).'plugins');
        define('FRAMEWORK_PLUGINS_DIR',     trailingslashit(FRAMEWORK_DIR).'plugins');
        
        define('FRAMEWORK_CUSTOMPOSTS',     trailingslashit(FRAMEWORK_URL).'customposts');
        define('FRAMEWORK_CUSTOMPOSTS_DIR',  trailingslashit(FRAMEWORK_DIR).'customposts');
        
        define('FRAMEWORK_ASSETS',          trailingslashit(FRAMEWORK_URL). 'assets');
        define('FRAMEWORK_ASSETS_DIR',      trailingslashit(FRAMEWORK_DIR). 'assets');
        define('FRAMEWORK_CSS',             trailingslashit(FRAMEWORK_ASSETS) . 'css');
        define('FRAMEWORK_CSS_DIR',         trailingslashit(FRAMEWORK_ASSETS_DIR) . 'css');
        define('FRAMEWORK_CSS_CACHE',       trailingslashit(FRAMEWORK_CSS) . 'cache');
        define('FRAMEWORK_CSS_CACHE_DIR',   trailingslashit(FRAMEWORK_CSS_DIR) . 'cache');
        define('FRAMEWORK_JS',              trailingslashit(FRAMEWORK_ASSETS) . 'js');
        define('FRAMEWORK_JS_DIR',          trailingslashit(FRAMEWORK_ASSETS_DIR) . 'js');
        define('FRAMEWORK_IMAGES',          trailingslashit(FRAMEWORK_ASSETS) . 'img');
        define('FRAMEWORK_IMAGES_DIR',      trailingslashit(FRAMEWORK_ASSETS_DIR) . 'img');
        
        define('FRAMEWORK_ADMIN',               trailingslashit(FRAMEWORK_URL).'admin');
        define('FRAMEWORK_ADMIN_DIR',           trailingslashit(FRAMEWORK_DIR).'admin');
        define('FRAMEWORK_ADMIN_ASSETS',        trailingslashit(FRAMEWORK_ADMIN).'assets');
        define('FRAMEWORK_ADMIN_ASSETS_DIR',    trailingslashit(FRAMEWORK_DIR).'assets');
        define('FRAMEWORK_ADMIN_CSS',           trailingslashit(FRAMEWORK_ADMIN_ASSETS).'css');
        define('FRAMEWORK_ADMIN_JS',            trailingslashit(FRAMEWORK_ADMIN_ASSETS).'js');
        define('FRAMEWORK_ADMIN_IMAGES',        trailingslashit(FRAMEWORK_ADMIN_ASSETS).'img');  
        
    }
    
    /**
     * Initialize actions and filters for framework
     * 
     * @since 0.1.0
     */
    protected function init(){
        
        //Load public scripts/styles
        add_action('wp_enqueue_scripts', array($this, 'loadPublicScripts'), 1);
        add_action('wp_enqueue_scripts', array($this, 'loadPublicStyles'), 1);
        add_action('wp_enqueue_scripts', array($this, 'loadFonts'), 1);

        //Load widgets from themebrew.ini file
        add_action( 'widgets_init', array($this, 'loadWidgetAreas'));
        
        //Allow shortcodes to be used in widgets.
        add_filter('widget_text', 'do_shortcode');
        
        //Add the Excerpt field to pages
        add_post_type_support( 'page', 'excerpt' );
        
        //Set memory_limit to 64M, mostly because WooCommerce likes it
        //Will most likely need to add -  define('WP_MEMORY_LIMIT', '64M');  - to the wp-config.php file so Wordpress sets the memory_limit to 64M as well.
        ini_set('memory_limit', '64M');  
        ini_set('post_max_size', '64M');  
        
        // Kill the "Notice: ob_end_flush(): failed to send buffer of zlib output compression" error
        // https://core.trac.wordpress.org/ticket/18525
        remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );
    }
    
    /**
     * Auto-Load files in the /classes folder
     * 
     * @since 0.1.0
     */
    protected function loadClasses(){
        foreach(glob(FRAMEWORK_CLASSES_DIR.'/*.php') as $file){
            $test = basename($file);
            if(!preg_match('/^_.*/', $test)){
                require_once($file);
            }
        }
    } 
    
    /**
     * Auto-Load files in the /widgets folder
     * 
     * @since 0.1.0
     */
    function loadWidgetAreas(){
        global $wp_registered_sidebars;
        if(isset($this->config['widget_areas']['areas'][0])){
            foreach($this->config['widget_areas']['areas'] as $area){
                
                $before_widget = '';
                $before_title = '';
                $after_widget = '';
                $after_title = '';
                $new_ops = array(
                    'title' => '',
                    'before_widget' => '',
                    'before_title' => '',
                    'after_widget' => '',
                    'after_title' => ''
                );
                if(strpos($area, 'before_title') !== false && strpos($area, 'after_title') !== false || strpos($area, 'before_widget') !== false && strpos($area, 'after_widget') !== false){
                    $ops = explode('|', $area);
                    $new_ops['title'] = $ops[0];
                    unset($ops[0]);
                    foreach($ops as $op){
                        
                        $op_arr = explode(':', $op);
                        $key = $op_arr[0];
                        $value = $op_arr[1];
                        $new_ops[$key] = $value;
                    }
                }else{
                    $new_ops['title'] = $area;
                }

                $id = self::slugify($new_ops['title']);
                
                $this->widgets[] = $new_ops['title'];
                
                if(array_key_exists($id, $wp_registered_sidebars)){
                    continue;
                }
                register_sidebar(array(
                    'name'          => __($new_ops['title']),
                    'id'            => $id,
                    'description'   => __($new_ops['title']),
                    'before_widget' => $new_ops['before_widget'],
                    'after_widget'  => $new_ops['after_widget'],
                    'before_title'  => $new_ops['before_title'],
                    'after_title'   => $new_ops['after_title']
                ));
            }
        }
    }  
    
    /**
     * Auto-Load files in the /shortcodes folder, if for some reason you do not
     * want a shortcode file added, add  '-rem' to the beginning of the file name.
     * Also sets the shortcodes array for active/inactive shortcodes per 'rem-'.
     * 
     * @since 0.1.0
     */
    protected function loadShortcodes(){
        foreach(glob(FRAMEWORK_SHORTCODES_DIR.'/*.php') as $file){
            $shortcode = basename($file);
            if(preg_match('/^_.*/', $shortcode)){  
                $this->shortcodes['inactive'][] = $shortcode;
            }else{
                $this->shortcodes['active'][] = $shortcode;
                require_once($file);
            }
        }
    }  

    /**
     * Load Custom Posts
     * 
     * @since 0.5.0
     */
    protected function loadWidgets(){
        foreach(glob(trailingslashit(THEME_DIR).'config/widgets/*.php') as $file){
            $test = basename($file);
            if(!preg_match('/^_.*/', $test)){
                require_once($file);
            }
        }
    }
    /**
     * Load Custom Posts
     * 
     * @since 0.5.0
     */
    protected function loadCustomPosts(){
        foreach(glob(trailingslashit(THEME_DIR).'config/customposts/*.php') as $file){
            $test = basename($file);
            if(!preg_match('/^_.*/', $test)){
                require_once($file);
            }
        }
    }
    
    /**
     * Load public scripts/styles
     * 
     * @since 0.1.0
     */
    function loadPublicScripts(){   
        wp_register_script('main', trailingslashit(THEME_JS).'app.min.js', array('jquery'), '1.0.0', true);
        wp_register_script('cycle', trailingslashit(THEME_JS).'lib/cycle2.js', array('jquery'), '1.0.0', true);
        //Enqueue scripts
        wp_enqueue_script('jquery');
        if($this->config['ini']['usecycle'] == "true")
            wp_enqueue_script('cycle');
        wp_enqueue_script('main');
    }
    
    /**
     * Load public scripts/styles
     * 
     * @since 1.0
     */
    function loadPublicStyles(){
        //Register custom fonts & style.css
        wp_register_style('main', trailingslashit(THEME_CSS).'style.min.css', false); 
        //Enqueue custom fonts & style.css
        wp_enqueue_style('main');
    }
    /**
     * Load public scripts/styles
     * 
     * @since 1.0
     */
    function loadFonts(){
        if(isset($this->config['google_fonts']['fonts'][0])){
            $google_fonts_url = "//fonts.googleapis.com/css?family=";
            $first = true;
            foreach($this->config['google_fonts']['fonts'] as $font){
                if($first){
                    $google_fonts_url .= urlencode($font);
                    $first = false;
                }else{
                    $google_fonts_url .= "|".urlencode($font);
                }
            }
        }
        wp_register_style('GoogleFonts', $google_fonts_url, false);
        wp_register_style('FontAwesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', false);
        wp_enqueue_style('GoogleFonts');
        wp_enqueue_style('FontAwesome');
    }
    
    /**
     * Return an instance of the framework class
     * 
     * @since 0.1.0
     * 
     * @return object A single instance of the framework class
     */
    static function get_instance(){
        if(null==self::$instance){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function slugify($str, $replace=array(), $delimiter='-', $maxLength=200) {

	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("%[^-/+|\w ]%", '', $clean);
	$clean = strtolower(trim(substr($clean, 0, $maxLength), '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
    }

    
    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone () {
            _doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', $this->_version );
    } // End __clone()

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup () {
            _doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', $this->_version );
    } // End __wakeup()

}
$TB = ThemeBrew::get_instance();