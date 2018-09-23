<?php

/**
 * Добавление скриптов и прочего
 */
function mac_add_to_favorites_scripts()
{
    if ( !is_single() || !is_user_logged_in() ) return;

//    Подключаем JS скрипты
    wp_enqueue_script('mac_add-to-favorites-js', plugins_url('/assets/js/mac_add-to-favorites.js', __FILE__), array('jquery'), null, false);
//    Подключаем CSS стили
    wp_enqueue_style('mac_add-to-favorites-css', plugins_url('/assets/css/mac_add-to-favorites.css', __FILE__));
//    Обьявляем глобальный обьект $post
    global $post;
//    Добавляем обьект с данными в файл /assets/js/mac_add-to-favorites.js
    wp_localize_script('mac_add-to-favorites-js','mac_obj', ['url'=>admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('mac_nonce'), 'post_id' => $post->ID] );
}

/**
 * Добавление ссылки Add to favorites или Remove from favorites перед контентом статьи
 * @param $content
 * @return string
 */
function mac_add_to_favorites($content)
{
    if ( !is_single() || !is_user_logged_in() ) return $content;
    $img_src = plugins_url('/assets/img/preloader.gif', __FILE__);

    global $post;
    if ( mac_is_favorites($post->ID) ) //Проверяем есть ли статья в БД
    {
        return '<p class="favorites-link"><a href="#" data-action="del" class="del-favorites-link">Remove from favorites</a><span class="mac-preloader"><img src="'. $img_src .'" alt=""></span></p>' . $content;
    }
    return '<p class="favorites-link"><a href="#" data-action="add" class="add-favorites-link">Add to favorites</a><span class="mac-preloader"><img src="'. $img_src .'" alt=""></span></p>' . $content;
}

/**
 * Добавление или удаление статей в user_meta
 */
function wp_ajax_mac_action()
{
    if (!wp_verify_nonce($_POST['security'], 'mac_nonce'))
    {
        wp_die('Security Error');
    }
    $post_id =  (int) $_POST['post_id'];
    $user = wp_get_current_user();

    if ( $_POST['post_action'] == 'add' )
    {
        if ( mac_is_favorites($post_id) ) wp_die('Added!');
        if( add_user_meta($user->ID, 'mac_atf', $post_id) )
        {
            wp_die('Added');
        }
    }
    if ( $_POST['post_action'] == 'del' )
    {
        if ( !mac_is_favorites($post_id) ) wp_die();
        if( delete_user_meta($user->ID, 'mac_atf', $post_id) )
        {
            wp_die('Removed');
        }
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

/**
 * Добавляем виджет в консоль администратора
 */
function mac_favorites_dashboard_widget()
{
    wp_add_dashboard_widget('mac_adf_dashboard', 'Favorites List', 'mac_show_dashboard_widget');
}

/**
 * Функция для вывода контента виджета
 */
function mac_show_dashboard_widget()
{
    $user = wp_get_current_user();
    $favorites = get_user_meta($user->ID, 'mac_atf');

    if (!$favorites)
    {
        echo 'List empty';
        return;
    }
    echo '<ul>';
    foreach ($favorites as $favorite)
    {
        echo '<li><a href="'.get_permalink($favorite).'" target="_blank" >' . get_the_title($favorite) . '</li></li>';
    }
    echo '</ul>';
}