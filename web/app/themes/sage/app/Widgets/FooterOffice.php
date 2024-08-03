<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class FooterOffice extends Widget
{
    /**
     * The widget name.
     *
     * @var string
     */
    public $name = 'Footer Office';

    /**
     * The widget description.
     *
     * @var string
     */
    public $description = 'This is a Footer Office widget.';

    /**
     * Data to be passed to the widget before rendering.
     */
    public function with(): array
    {
        return [
            'title_office' => $this->title_office(),
            'content_office' => $this->content(),
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
        return get_field('title_office', $this->widget->id) ?: 'Insert a title' ;
    }
    /**
     * The widget content.
     */
    public function content()
    {
        return get_field('content_office', $this->widget->id) ?: 'Insert a content';
    }

    /**
     * The widget field group.
     */
    public function fields(): array
    {
        $footerOffice = Builder::make('footer_office');

        $footerOffice
            ->addText('title_office', [
                'label' => 'Title',
                'required' => true,
                'default_value' => 'Our Office',
            ])
            ->addWysiwyg('content_office', [
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

        return $footerOffice->build();
    }
    
}
