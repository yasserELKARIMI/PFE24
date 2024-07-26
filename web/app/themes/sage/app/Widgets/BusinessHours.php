<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class BusinessHours extends Widget
{
    public $name = 'Business Hours';

    public $description = 'This is a Business Hours widget.';

    public function with(): array
    {
        return [
            'title_BusinessHours' => $this->title_BusinessHours(),
            'content_Businesshours' => $this->content(),
        ];
    }

    public function title() {}

    public function title_BusinessHours()
    {
        return get_field('title_BusinessHours', $this->widget->id) ?: 'Insert a title';
    }

    public function content()
    {
        return get_field('content_Businesshours', $this->widget->id) ?: '<p class="mb-1">Monday - Friday</p>
        <h6 class="text-light">09:00 am - 07:00 pm</h6>
        <p class="mb-1">Saturday</p>
        <h6 class="text-light">09:00 am - 12:00 pm</h6>
        <p class="mb-1">Sunday</p>
        <h6 class="text-light">Closed</h6>';
    }

    public function fields(): array
    {
        $businessHours = Builder::make('business_hours');

        $businessHours
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

        return $businessHours->build();
    }
}
