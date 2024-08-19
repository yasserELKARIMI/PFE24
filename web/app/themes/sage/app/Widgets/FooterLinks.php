<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class FooterLinks extends Widget
{
    /**
     * The widget name.
     *
     * @var string
     */
    public $name = 'Footer Links';

    /**
     * The widget description.
     *
     * @var string
     */
    public $description = 'This is a Footer Links widget.';

    /**
     * Data to be passed to the widget before rendering.
     */
    public function with(): array
    {
        return [
            'title_Links' => $this->title_Links(),
            'menu_name' => $this->menu_name(),
        ];
    }

    /**
     * The widget title.
     */
    public function title(){}

    public function title_Links()
    {
        return get_field('title_Links', $this->widget->id) ?: 'Insert a title' ;
    }
    
    /**
     * The widget field group.
     */
    public function fields(): array
    {
        $footerLinks = Builder::make('footer_links');

        $footerLinks
        ->addText('title_Links', [
            'label' => 'Title',
            'required' => true,
            'default_value' => 'Quick Links',
        ])
        ->addSelect('menu_name', [
            'label' => 'Select Menu',
            'choices' => $this->getMenus(),
            'return_format' => 'value',
            'ui' => 1,
            'allow_null' => 0,
            'multiple' => 0,
        ]);
        
        return $footerLinks->build();
    }

    /**
     * Retrieve available menus.
     *
     * @return array
     */
    public function getMenus(): array
    {
        $menus = wp_get_nav_menus();
        $menu_options = [];

        foreach ($menus as $menu) {
            $menu_options[$menu->slug] = $menu->name;
        }

        return $menu_options;
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function menu_name()
    {
        return get_field('menu_name', $this->widget->id);
    }
}