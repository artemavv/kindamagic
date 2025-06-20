<?php
/**
 * WP Bootstrap Starter Child Theme functions and definitions
 *
 * @package WP_Bootstrap_Starter_Child
 */

// Enqueue parent theme styles
function wp_bootstrap_starter_child_enqueue_styles() {
    wp_enqueue_style('wp-bootstrap-starter-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('wp-bootstrap-starter-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('wp-bootstrap-starter-style'), filemtime( get_stylesheet_directory() . '/style.css' )
    );
}
add_action('wp_enqueue_scripts', 'wp_bootstrap_starter_child_enqueue_styles');

// Add custom post type named "folder"
function register_folder_post_type() {
    $labels = array(
        'name'               => 'Gallery',
        'singular_name'      => 'Gallery',
        'menu_name'          => 'Galleries',
        'add_new'           => 'Add New',
        'add_new_item'      => 'Add New Gallery',
        'edit_item'         => 'Edit Gallery',
        'new_item'          => 'New Gallery',
        'view_item'         => 'View Gallery',
        'search_items'      => 'Search Galleries',
        'not_found'         => 'No galleries found',
        'not_found_in_trash'=> 'No galleries found in Trash'
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'gallery'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'thumbnail', 'page-attributes'),
        'exclude_from_search'=> true // Makes the post type not searchable
    );

    register_post_type('folder', $args);
}
add_action('init', 'register_folder_post_type');
