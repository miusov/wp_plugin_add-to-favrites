<?php

/**
 * Добавление скриптов и прочего
 */
function mac_add_to_favorites_scripts()
{
    if ( !is_single() || !is_user_logged_in() ) return;

//    Подключаю JS скрипты
    wp_enqueue_script('mac_add-to-favorites-js', plugins_url('/assets/js/mac_add-to-favorites.js', __FILE__), array('jquery'), null, false);
//    Подключаю CSS стили
    wp_enqueue_style('mac_add-to-favorites-css', plugins_url('/assets/css/mac_add-to-favorites.css', __FILE__));
//    Обьявляю глобальный обьект $post
    global $post;
//    Добавляю обьект с данными в файл /assets/js/mac_add-to-favorites.js
    wp_localize_script('mac_add-to-favorites-js','mac_obj', ['url'=>admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('mac_nonce'), 'post_id' => $post->ID] );
}

/**
 * Добавление ссылки Add to favorites перед контентом статьи
 * @param $content
 * @return string
 */
function mac_add_to_favorites($content)
{
    if ( !is_single() || !is_user_logged_in() ) return $content;
    $img_src = plugins_url('/assets/img/preloader.gif', __FILE__);

    global $post;
    if ( mac_is_favorites($post->ID) )
    {
        return '<p><a href="#" class="rm-favorites-link">Remove from favorites</a></p>' . $content;
    }

    return '<p><a href="#" class="add-favorites-link">Add to favorites</a><span class="mac-preloader"><img src="'. $img_src .'" alt=""></span></p>' . $content;
}

/**
 * AJAX
 */
function wp_ajax_mac_atf()
{
    if (!wp_verify_nonce($_POST['security'], 'mac_nonce'))
    {
        wp_die('Security Error');
    }
    $post_id =  (int) $_POST['post_id'];
    $user = wp_get_current_user();

    if ( mac_is_favorites($post_id) ) wp_die('Added!');

    if( add_user_meta($user->ID, 'mac_atf', $post_id) )
    {
        wp_die('Added');
    }

    wp_die('Added error');
}

/**
 * Проверка на наличие статьи в БД
 * @param $post_id
 * @return bool
 */
function mac_is_favorites($post_id)
{
    $user = wp_get_current_user();
    $favorites = get_user_meta($user->ID, 'mac_atf');
    foreach ($favorites as $favorite)
    {
        if ($favorite == $post_id) return true;
    }
    return false;
}

