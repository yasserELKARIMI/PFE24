<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class Newsletter extends Widget
{
    public $name = 'Newsletter';

    public $description = 'This is a Newsletter widget.';

    public function with(): array
    {
        return [
            'title' => $this->title(),
            'content' => $this->content(),
        ];
    }

    public function title() {}

    public function title_Newsletter()
    {
        return get_field('title_Newsletter', $this->widget->id) ?: 'Insert a title';
    }

    public function content()
    {
        return get_field('content_Newsletter', $this->widget->id) ?: 'Insert a content';
    }

    public function fields(): array
    {
        $newsletter = Builder::make('newsletter');

        $newsletter
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
                    <button type="button" class="btn btn-secondary py-2 position-absolute top-0 end-0 mt-2 me-2" style="background-color: #EDDD5E">SignUp</button>
                </div>',
            ]);

        return $newsletter->build();
    }
}
