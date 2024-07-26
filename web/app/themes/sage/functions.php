<?php
// require_once get_template_directory() . '/app/Widgets/ExampleCustom_Widget'; // Adjust path as needed

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our theme. We will simply require it into the script here so that we
| don't have to worry about manually loading any of our classes later on.
|
*/

if (! file_exists($composer = __DIR__.'/vendor/autoload.php')) {
    wp_die(__('Error locating autoloader. Please run <code>composer install</code>.', 'sage'));
}

require $composer;

/*
|--------------------------------------------------------------------------
| Register The Bootloader
|--------------------------------------------------------------------------
|
| The first thing we will do is schedule a new Acorn application container
| to boot when WordPress is finished loading the theme. The application
| serves as the "glue" for all the components of Laravel and is
| the IoC container for the system binding all of the various parts.
|
*/

if (! function_exists('\Roots\bootloader')) {
    wp_die(
        __('You need to install Acorn to use this theme.', 'sage'),
        '',
        [
            'link_url' => 'https://roots.io/acorn/docs/installation/',
            'link_text' => __('Acorn Docs: Installation', 'sage'),
        ]
    );
}

\Roots\bootloader()->boot();

/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

// function register_footer_widgets() {
//     register_sidebar(array(
//         'name' => __('Office Widget Area'),
//         'id' => 'office_widget_area',
//         'description' => __('Widget area for office information.'),
//         'before_widget' => '<div class="widget-container %1$s %2$s">',
//         'after_widget' => '</div>',
//         'before_title' => '<h5 class="widget-title">',
//         'after_title' => '</h5>',
//     ));

//     register_sidebar(array(
//         'name' => __('Quick Links Widget Area'),
//         'id' => 'quick_links_widget_area',
//         'description' => __('Widget area for quick links.'),
//         'before_widget' => '<div class="widget-container %1$s %2$s">',
//         'after_widget' => '</div>',
//         'before_title' => '<h5 class="widget-title">',
//         'after_title' => '</h5>',
//     ));

//     register_sidebar(array(
//         'name' => __('Business Hours Widget Area'),
//         'id' => 'business_hours_widget_area',
//         'description' => __('Widget area for Business Hours.'),
//         'before_widget' => '<div class="widget-container %1$s %2$s">',
//         'after_widget' => '</div>',
//         'before_title' => '<h5 class="widget-title">',
//         'after_title' => '</h5>',
//     ));

//     register_sidebar(array(
//         'name' => __('Newsletter Widget Area'),
//         'id' => 'newsletter_widget_area',
//         'description' => __('Widget area for Newsletter.'),
//         'before_widget' => '<div class="widget-container %1$s %2$s">',
//         'after_widget' => '</div>',
//         'before_title' => '<h5 class="widget-title">',
//         'after_title' => '</h5>',
//     ));
// }
// add_action('widgets_init', 'register_footer_widgets');

// function enqueue_custom_post_styles() {
//         wp_enqueue_style('main', get_template_directory_uri() . '/public/styles/app.css');
//         wp_enqueue_style('bootstrap', get_template_directory_uri() . '/public/styles/bootstrap.min.css');
//         wp_enqueue_script('bootstrap', get_template_directory_uri() . '/public/js/main.js');
      
    
// }
// add_action('admin_enqueue_scripts', 'enqueue_custom_post_styles');


// // Include the WP_Bootstrap_Navwalker class
// require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

// // Register the primary menu
// function register_primary_menu() {
//     register_nav_menus(array(
//         'primary_navigation' => __('Primary Navigation', 'your-text-domain'),
//     ));
// }
// add_action('after_setup_theme', 'register_primary_menu');
// function add_active_class_to_nav_menu($classes, $item, $args) {
//     if (isset($args->theme_location) && $args->theme_location === 'primary_navigation') {
//         if (in_array('current-menu-item', $classes) || in_array('current-menu-parent', $classes)) {
//             $classes[] = 'active';
//         }
//     }
//     return $classes;
// }
// add_filter('nav_menu_css_class', 'add_active_class_to_nav_menu', 10, 3);

// Widgets for the footer code
collect(['setup', 'filters'])
    ->each(function ($file) {
        if (! locate_template($file = "app/{$file}.php", true, true)) {
            wp_die(
                /* translators: %s is replaced with the relative file path */
                sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
            );
        }
    });


    require_once get_template_directory() . '/app/inc/class-wp-bootstrap-navwalker.php';

    add_action('after_setup_theme', function () { 
        add_editor_style( '/public/styles/app.css');
        add_editor_style('/public/styles/bootstrap.min.css');
        add_editor_style('/public/js/main.js');    
});
add_action('after_setup_theme', function () {
    add_theme_support('editor-styles');  // Enable support for editor styles.
    add_editor_style('/public/styles/fontawesome/css/all.min.css');
    add_editor_style('/public/styles/bootstrap-icons/font/bootstrap-icons.min.css');
    add_editor_style('/public/lib/animate/animate.min.css');
    add_editor_style('/public/lib/owlcarousel/assets/owl.carousel.min.css');
    add_editor_style('/public/lib/lightbox/css/lightbox.min.css');
    add_editor_style('/public/styles/bootstrap.min.css');  
    add_editor_style('/public/styles/app.css');  
});
add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script('jquery');
    wp_enqueue_script('sage-editor-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js', ['jquery'], false, true);
    // wp_enqueue_script('sage-editor-wow', get_template_directory_uri() . '/public/lib/wow/wow.min.js', ['jquery'], false, true);
});
// Include the WP_Bootstrap_Navwalker class

// Register the primary menu
function register_primary_menu() {
    register_nav_menus(array(
        'primary_navigation' => __('Primary Navigation', 'your-text-domain'),
    ));
}
add_action('after_setup_theme', 'register_primary_menu');
function add_active_class_to_nav_menu($classes, $item, $args) {
    if (isset($args->theme_location) && $args->theme_location === 'primary_navigation') {
        if (in_array('current-menu-item', $classes) || in_array('current-menu-parent', $classes)) {
            $classes[] = 'active';
        }
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_active_class_to_nav_menu', 10, 3);

