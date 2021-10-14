<?php

function registrando_taxonomia()
{
    register_taxonomy(
        'animais',
        'adoçao',
        array(
            'labels' => array('name' => 'Animais'),
            'hierarchical' => true
        )
    );
}

add_action('init', 'registrando_taxonomia');

function registrando_post_customizado()
{
    register_post_type('adoçao',
        array(
            'labels' => array('name' => 'Adoçao'),
            'public' => true,
            'menu_position' => 0,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-admin-site'
        )
    );
}

add_action('init', 'registrando_post_customizado');

function adicionando_recursos_ao_tema()
{
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'adicionando_recursos_ao_tema');

function registrando_menu()
{
    register_nav_menu(
        'menu-navegacao',
        'Menu navegação'
    );
}

add_action('init', 'registrando_menu');

function registrando_post_customizado_banner()
{
    register_post_type(
        'banners',
        array(
            'labels' => array('name' => 'Banner'),
            'public' => true,
            'menu_position' => 1,
            'menu_icon' => 'dashicons-format-image',
            'supports' => array('title', 'thumbnail')
        )
    );
}

add_action('init', 'registrando_post_customizado_banner');

function registrando_metabox()
{
    add_meta_box(
        'registrando_metabox',
        'Texto para a home',
        'funcao_callback',
        'banners'
    );
}

add_action('add_meta_boxes', 'registrando_metabox');

function funcao_callback($post)
{

    $texto_1 = get_post_meta($post->ID, '_texto_1', true);
    $texto_2 = get_post_meta($post->ID, '_texto_2', true);
    ?>
    <label for="texto_1">Texto 1</label>
    <input type="text" name="texto_1" style="width: 100%" value="<?= $texto_1 ?>"/>
    <br>
    <br>
    <label for="texto_2">Texto 2</label>
    <input type="text" name="texto_2" style="width: 100%" value="<?= $texto_2 ?>"/>
    
    <?php
}

function salvando_dados_metabox($post_id)
{
    foreach ($_POST as $key => $value) {
        if ($key !== 'texto_1' && $key !== 'texto_2') {
            continue;
        }

        update_post_meta(
            $post_id,
            '_' . $key,
            $_POST[$key]
        );
    }
}

add_action('save_post', 'salvando_dados_metabox');

function TextosParaBanner()
{

    $args = array(
        'post_type' => 'banners',
        'post_status' => 'publish',
        'posts_per_page' => 1
    );

    $query = new WP_Query($args);
    if ($query->have_posts()):
        while ($query->have_posts()): $query->the_post();
            $texto1 = get_post_meta(get_the_ID(), '_texto_1', true);
            $texto2 = get_post_meta(get_the_ID(), '_texto_2', true);
            return array(
                'texto_01' => $texto1,
                'texto_02' => $texto2
            );
        endwhile;
    endif;
}

function adicionando_scripts()
{

    $textosBanner = TextosParaBanner();

    if (is_front_page()) {
        wp_enqueue_script('typed-js', get_template_directory_uri() . '/js/typed.min.js', array(), false, true);
        wp_enqueue_script('texto-banner-js', get_template_directory_uri() . '/js/texto-banner.js', array('typed-js'), false, true);
        wp_localize_script('texto-banner-js', 'data', $textosBanner);
    }
}

add_action('wp_enqueue_scripts', 'adicionando_scripts');