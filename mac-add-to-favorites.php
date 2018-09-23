<?php
/*
Plugin Name: Add to favorites
Plugin URI: http://example.com
Description: Добавление в избранное статей
Version: 1.0
Author: artem_miusov
Author URI: https://github.com/miusov
*/

require __DIR__ . '/functions.php';

add_filter('the_content','mac_add_to_favorites');
add_action('wp_enqueue_scripts', 'mac_add_to_favorites_scripts');
add_action('wp_ajax_mac_action','wp_ajax_mac_action');
add_action('wp_dashboard_setup', 'mac_favorites_dashboard_widget');