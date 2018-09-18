<?php

//ADD JS SCRIPTS AND CSS
function mac_add_to_favorites_scripts()
{
    if ( !is_single() || !is_user_logged_in() ) return;

    wp_enqueue_script('mac_add-to-favorites-js', plugins_url('/assets/js/mac_add-to-favorites.js', __FILE__), array('jquery'), null, false);
    wp_enqueue_style('mac_add-to-favorites-css', plugins_url('/assets/css/mac_add-to-favorites.css', __FILE__));

}


function mac_add_to_favorites($content)
{
    if ( !is_single() || !is_user_logged_in() ) return $content;
    return '<p><a href="#" class="favorites-link">Add to favorites</a></p>' . $content;
}

