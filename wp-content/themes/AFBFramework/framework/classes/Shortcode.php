<?php if (! defined( 'ABSPATH' )) { header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!'); }
if ( ! class_exists( 'Shortcode' ) ) {
    class Shortcode{

        protected function removeHtmlTags($content,$p=false,$br=false){	
            $content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
            $content = preg_replace('#<br \/>#', '', $content);

            if ( $p ) $content = preg_replace('#<p>|</p>#', '', $content);

            return trim($content);
        }

        protected function contentHelper($content,$p=false,$br=false){
            return self::removeHtmlTags( do_shortcode(shortcode_unautop($content)), $p, $br);
        }
    }
}