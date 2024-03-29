<?php

// Load Redux extensions - MUST be loaded before your options are set
if (file_exists(dirname(__FILE__).'/redux-extensions/extensions-init.php')) {
    require_once( dirname(__FILE__).'/redux-extensions/extensions-init.php' );
}    
// Load the embedded Redux Framework
if (file_exists(dirname(__FILE__).'/redux-framework/ReduxCore/framework.php')) {
    require_once( dirname(__FILE__).'/redux-framework/ReduxCore/framework.php' );
}
// Load the theme/plugin options

if(file_exists(THEME_DIR.'/config/options-init.php')){
    require_once(THEME_DIR.'/config/options-init.php');
}else{
    if (file_exists(dirname(__FILE__).'/options-init.php')) {
        require_once( dirname(__FILE__).'/options-init.php' );
    }
}