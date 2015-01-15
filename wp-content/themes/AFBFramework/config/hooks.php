<?php

function new_excerpt_more( $more ) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');

function change_excerpt_length() {
    return 25;
}
add_filter( 'excerpt_length', 'change_excerpt_length', 999 );