<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
});

add_action('after_setup_theme', function () {
    add_theme_support('editor-styles');  // Enable support for editor styles.
    add_editor_style('/public/styles/fontawesome/css/all.min.css');
    add_editor_style('/public/styles/bootstrap-icons/font/bootstrap-icons.min.css');
    add_editor_style('/public/lib/animate/animate.min.css');
    add_editor_style('/public/lib/owlcarousel/assets/owl.carousel.min.css');
    add_editor_style('/public/lib/lightbox/css/lightbox.min.css');
    add_editor_style('/public/styles/bootstrap.min.css');  // Prioritize bootstrap styles.
    add_editor_style('/public/styles/app.css');  // Main theme styles for the editor.
});

add_action('enqueue_block_editor_assets', function () {
     wp_enqueue_script('jquery');
     wp_enqueue_script('sage-editor-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js', ['jquery'], false, true);
     // Add other scripts similarly, ensuring you follow dependencies
     wp_enqueue_script('sage-editor-wow', get_template_directory_uri() . '/public/lib/wow/wow.min.js', ['jquery'], false, true);
     wp_enqueue_script('sage-editor-easing', get_template_directory_uri() . '/public/lib/easing/easing.min.js', ['jquery'], false, true);
     wp_enqueue_script('sage-editor-waypoints', get_template_directory_uri() . '/public/lib/waypoints/waypoints.min.js', ['jquery, sage-editor-bootstrap, sage-editor-wow'], false, true);
     wp_enqueue_script('sage-editor-counterup', get_template_directory_uri() . '/public/lib/counterup/counterup.min.js', ['jquery, sage-editor-bootstrap'], false, true);
     wp_enqueue_script('sage-editor-parallax', get_template_directory_uri() . '/public/lib/parallax/parallax.min.js', ['jquery, sage-editor-bootstrap'], false, true);
     wp_enqueue_script('sage-editor-lightbox', get_template_directory_uri() . '/public/lib/lightbox/js/lightbox.min.js', ['jquery, sage-editor-bootstrap'], false, true);
 });
