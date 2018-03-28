<?php
/**
 * Plugin Name: my_post_rating
 * Description: Плагин для оценки поста
 */


function mpr_post_like(){
    // Проверка безопасности
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'ajax-nonce')) {
        die;
    }

    if (isset($_POST['post_like'])) {
        // Получаем IP-адрес пользователя
        $ip = $_SERVER['REMOTE_ADDR'];
        // id поста
        $post_id = $_POST['post_id'];
        // имя класса ссылки(up/down) 
        $class_a = $_POST['class_a'];

        // Получаем IP проголосовавших за текущий пост, если такие есть
        $meta_IP = get_post_meta($post_id, "voted_IP");
        $voted_IP = $meta_IP[0];

        if (!is_array($voted_IP)) {
            $voted_IP = array();
        }

        // Сколько проголосовало
        $meta_count = get_post_meta($post_id, "votes_count", true);

        // Проверяем голосовал ли пользователь
        $voted_IP[$ip] = time();

        if (in_array($ip, array_keys($voted_IP))) {
            $voice = get_post_meta($post_id, "voice");
            $voice = $voice[0];
        } else {
            $voice = '';
        }

        if ($class_a == 'up' and $class_a != $voice) {
            update_post_meta($post_id, "voted_IP", $voted_IP);
            update_post_meta($post_id, "voice", 'up');
            update_post_meta($post_id, "votes_count", ++$meta_count);
            echo $meta_count;
        } elseif ($class_a == 'down' and $class_a != $voice) {
            update_post_meta($post_id, "voted_IP", $voted_IP);
            update_post_meta($post_id, "voice", 'down');
            update_post_meta($post_id, "votes_count", --$meta_count);
            if ($meta_count < 0) {
                $meta_count = 0;
                update_post_meta($post_id, "votes_count", $meta_count);
            }
            echo $meta_count;
        } else {
            echo $meta_count;
        }

    }
    exit;
}

add_action('wp_ajax_nopriv_post-like', 'mpr_post_like');
add_action('wp_ajax_post-like', 'mpr_post_like');


function mpr_get_post_rating() {
    global $id;
        
    if($id === 0) {
        $post_id = get_the_ID();
    } elseif (is_null($id)) {
        global $post;
        $post_id = $post->ID;
    } else {
        $post_id = $id;
    }
    
    $vote_count = get_post_meta($post_id, "votes_count", true);

    $output = '<p class="post-like">';

    $output .= '<a href="#" data-post_id="' . $post_id . '" class="up"><img src="' . plugins_url('my_post_rating/assets/img/up.png') . '"></a>';

    $output .= '<span class="count">' . $vote_count . '</span>';

    $output .= '<a href="#" data-post_id="' . $post_id . '" class="down"><img src="' . plugins_url('my_post_rating/assets/img/down.png') . '"></a>';

    $output .= '</p>';

    echo $output;
}

add_action('mpr_post_rating', 'mpr_get_post_rating');


function mpr_load_script(){

    /*Post rating script*/
    wp_enqueue_script('like_post', get_template_directory_uri() . '/assets/js/post-like.js', array('jquery'), '1.0', true);
    wp_localize_script('like_post', 'ajax_var', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce')
    ));
}

add_action('wp_enqueue_scripts', 'mpr_load_script');
