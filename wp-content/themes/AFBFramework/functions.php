<?php
/**
 * @package WordPress
 * @subpackage ThemeBrew
 */

//Include & startup Framework
require_once(get_template_directory().'/framework/framework.php');

// filter the Gravity Forms button type
add_filter("gform_submit_button", "form_submit_button", 10, 2);
function form_submit_button($button, $form){
    return "<button class='button btn btn-primary gf-submit' id='gform_submit_button_{$form["id"]}'><span>Submit</span></button>";
}