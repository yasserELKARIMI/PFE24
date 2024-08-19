<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class FooterBusinessHours extends Widget
{
    /**
     * The widget name.
     *
     * @var string
     */
    public $name = 'Footer Business Hours';

    /**
     * The widget description.
     *
     * @var string
     */
    public $description = 'This is a Footer Business Hours widget.';

    /**
     * Data to be passed to the widget before rendering.
     */
    public function with(): array
    {
        return [
            // 'title' => $this->title(),
            'title_BusinessHours' => $this->title(),
            'content_Businesshours' => $this->content(),
        ];
    }

    /**
     * The widget title.
     */
    // public function title(): string
    // {
    //     return get_field('title', $this->widget->id);
    // }

    /**
     * The displayed title.
     */
    public function title()
    {
        return get_field('title_BusinessHours', $this->widget->id) ?: 'Insert a title' ;
    }

    /**
     * The widget content.
     */
    public function content(): string
    {
        return get_field('content_Businesshours', $this->widget->id) ?: '<p class="mb-1">Monday - Friday</p>
        <h6 class="text-light">09:00 am - 07:00 pm</h6>
        <p class="mb-1">Saturday</p>
        <h6 class="text-light">09:00 am - 12:00 pm</h6>
        <p class="mb-1">Sunday</p>
        <h6 class="text-light">Closed</h6>';
    }


    /**
     * The widget field group.
     */
    public function fields(): array
    {
        $footerBusinessHours = Builder::make('footer_business_hours');

        $footerBusinessHours
        ->addText('title_BusinessHours', [
            'label' => 'Title',
            'required' => true,
            'default_value' => 'Business Hours',
        ])
        ->addWysiwyg('content_Businesshours', [
            'label' => 'Content',
            'required' => true,
            'default_value' => '<p class="mb-1">Monday - Friday</p>
            <h6 class="text-light">09:00 am - 07:00 pm</h6>
            <p class="mb-1">Saturday</p>
            <h6 class="text-light">09:00 am - 12:00 pm</h6>
            <p class="mb-1">Sunday</p>
            <h6 class="text-light">Closed</h6>',
        ]);

        return $footerBusinessHours->build();
    }

}
