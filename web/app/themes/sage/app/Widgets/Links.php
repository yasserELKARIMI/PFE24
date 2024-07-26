<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class Links extends Widget
{
    public $name = 'Links';

    public $description = 'This is a Links widget.';

    public function with(): array
    {
        return [
            'title' => $this->title(),
            'menu_name' => $this->menu_name(),
        ];
    }

    public function title() {}

    public function title_Links()
    {
        return get_field('title_Links', $this->widget->id) ?: 'Insert a title';
    }

    public function fields(): array
    {
        $links = Builder::make('links');

        $links
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

        return $links->build();
    }

    public function getMenus(): array
    {
        $menus = wp_get_nav_menus();
        $menu_options = [];

        foreach ($menus as $menu) {
            $menu_options[$menu->slug] = $menu->name;
        }

        return $menu_options;
    }

    public function menu_name()
    {
        return get_field('menu_name', $this->widget->id);
    }
}
