<?php
namespace App\Options;

class Bexio
{
    public function __construct()
    {
        add_action('acf/init', [$this, 'addOptionsPages']);
    }

    public function addOptionsPages()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title' => 'Bexio Settings',
                'menu_title' => 'Bexio',
                'menu_slug'  => 'bexio-settings',
                'capability' => 'manage_options',
                'redirect'   => false,
            ]);

            acf_add_options_sub_page([
                'page_title'  => 'Settings',
                'menu_title'  => 'Settings',
                'parent_slug' => 'bexio-settings',
                'capability'  => 'manage_options',
            ]);
        }
    }

}
