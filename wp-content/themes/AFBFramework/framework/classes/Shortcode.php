<?php if (! defined( 'ABSPATH' )) { header('HTTP/1.0 403 Forbidden'); die('No direct file access allowed!'); }
if ( ! class_exists( 'Shortcode' ) ) {
    class Shortcode{

        static protected function removeHtmlTags($content,$p=false,$br=false){	
            $content = preg_replace('#^<\/p>|^<br \/>|<p>$#', '', $content);
            $content = preg_replace('#<br \/>#', '', $content);

            if ( $p ) $content = preg_replace('#<p>|</p>#', '', $content);

            return trim($content);
        }

        static protected function contentHelper($content,$pre='c'){
            if ( strpos( $content, '[_' ) !== false ) $content = preg_replace( '@(\[_*)_(' . $pre . '|/)@', "$1$2", $content );
            return do_shortcode(shortcode_unautop($content));
        }
    }
}