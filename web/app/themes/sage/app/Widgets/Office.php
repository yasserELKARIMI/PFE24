<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class Office extends Widget
{
    /**
     * The widget name.
     *
     * @var string
     */
    public $name = 'Office';

    /**
     * The widget description.
     *
     * @var string
     */
    public $description = 'This is a Office widget.';

    /**
     * Data to be passed to the widget before rendering.
     */
    public function with(): array
    {
        return [
            'title' => $this->title_office(),
            'content' => $this->content(),
        ];
    }

    /**
     * The widget title.
     */
    public function title(){}

    /**
     * The displayed title.
     */
    public function title_office()
    {
        return get_field('title', $this->widget->id) ?: 'Insert a title' ;
    }
    /**
     * The widget content.
     */
    public function content()
    {
        return get_field('content', $this->widget->id) ?: 'Insert a content';
    }

    /**
     * The widget field group.
     */
    public function fields(): array
    {
        $Office = Builder::make('office');

        $Office
            ->addText('title', [
                'label' => 'Title',
                'required' => true,
                'default_value' => 'Our Office',
            ])
            ->addWysiwyg('content', [
                'label' => 'Content',
                'required' => true,
                'default_value' => '<p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Insert your business address </p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>05 12 34 56 78</p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>yourbusinessemail@example.com</p>
                <div class="d-flex pt-3">
                    <a class="btn btn-square btn-secondary rounded-circle me-2" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-square btn-secondary rounded-circle me-2" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-square btn-secondary rounded-circle me-2" href=""><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-square btn-secondary rounded-circle me-2" href=""><i class="fab fa-linkedin-in"></i></a>
                </div>',
            ]);

        return $Office->build();
    }
    
}
