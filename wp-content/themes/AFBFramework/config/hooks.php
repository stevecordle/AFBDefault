<?php
if($_GET['activated'] && is_admin()){
        
    $samplePage = get_page_by_title('Sample Page');
    $samplePost = get_post('Hello world!');
    wp_delete_post($samplePage->ID, true);
    wp_delete_post($samplePost->ID, true);
    if(isset($this->config['pages']['title'][0])){
        foreach($this->config['pages']['title'] as $title){
            $check = get_page_by_title($title);
            if(!isset($check->ID)){
                wp_insert_post(
                    array(
                        'post_type' => 'page',
                        'post_title' => $title,
                        'post_content' => self::$lorem_ipsum,
                        'post_status' => 'publish',
                        'post_author' => 1
                    )
                );
            }
        }
    }
}
function new_excerpt_more( $more ) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

function change_excerpt_length() {
    return 25;
}
add_filter( 'excerpt_length', 'change_excerpt_length', 999 );