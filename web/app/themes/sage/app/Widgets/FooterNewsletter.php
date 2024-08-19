<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class FooterNewsletter extends Widget
{
    /**
     * The widget name.
     *
     * @var string
     */
    public $name = 'Footer Newsletter';

    /**
     * The widget description.
     *
     * @var string
     */
    public $description = 'This is a Footer Newsletter widget.';

    /**
     * Data to be passed to the widget before rendering.
     */
    public function with(): array
    {
        return [
            'title_Newsletter' => $this->title_Newsletter(),
            'content_Newsletter' => $this->content(),
        ];
    }

    /**
     * The widget title.
     */
    public function title(){}

        /**
     * The displayed title.
     */
    public function title_Newsletter()
    {
        return get_field('title_Newsletter', $this->widget->id) ?: 'Insert a title' ;
    }

    /**
     * The widget content.
     */
    public function content()
    {
        return get_field('content_Newsletter', $this->widget->id) ?: 'Insert a content';
    }


    /**
     * The widget field group.
     */
    public function fields(): array
    {
        $footerNewsletter = Builder::make('footer_newsletter');

        $footerNewsletter
        ->addText('title_Newsletter', [
            'label' => 'Title',
            'required' => true,
            'default_value' => 'Newsletter',
        ])
        ->addWysiwyg('content_Newsletter', [
            'label' => 'Content',
            'required' => true,
            'default_value' => '<p>Dont miss our updates. Sign in to our news letter today</p>
            <div class="position-relative w-100">
                <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                <button type="button" class="btn btn-secondary py-2 position-absolute top-0 end-0 mt-2 me-2">Sign Up</button>
            </div>',
        ]);

        return $footerNewsletter->build();
    }

}
