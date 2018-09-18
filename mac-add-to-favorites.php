<?php
/*
Plugin Name: Add to favorites
Plugin URI: http://example.com
Description: Добавление в избранное статей
Version: 1.0
Author: artem_miusov
Author URI: https://github.com/miusov
*/

add_filter('the_content','mac_add_to_favorites');

function mac_add_to_favorites($content){
    if ( !is_single() || !is_user_logged_in() ) return $content;
    return '<p><a href="#" class="favorites-link">Add to favorites</a></p>' . $content;
}