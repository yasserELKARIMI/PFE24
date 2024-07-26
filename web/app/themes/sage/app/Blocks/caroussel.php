<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Caroussel extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Caroussel';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Caroussel block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'formatting';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'editor-ul';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => false,
        'multiple' => true,
        'jsx' => true,
        'color' => [
            'background' => true,
            'text' => true,
            'gradient' => true,
        ],
    ];

    /**
     * The block fields.
     */
    public function fields(): array
    {
        $carousel = Builder::make('carousel');

        $carousel
            
            ->addRepeater('items', [
                'label' => 'Background',
                'min' => 1,
                'layout' => 'block',
            ])
                ->addImage('background', [
                    'label' => 'Background Image',
                    'required' => true,
                ])
                ->addSelect('alignment', [
                    'label' => 'Alignment',
                    'choices' => [
                        'start' => 'Start',
                        'center' => 'Center',
                        'end' => 'End',
                    ],
                    'default_value' => 'start',
                ])
                ->addSelect('text_alignment', [
                    'label' => 'Text Alignment',
                    'choices' => [
                        'start' => 'Start',
                        'center' => 'Center',
                        'end' => 'End',
                    ],
                    'default_value' => 'start',
                ])
                ->addTextarea('paragraph', [
                    'label' => 'Paragraph',
                    'rows' => 3,
                ])
                ->addText('title', [
                    'label' => 'Title',
                ])
                ->addUrl('explore_more_link', [
                    'label' => 'Explore More Link',
                ])
            ->endRepeater();

        return $carousel->build();
    }

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'items' => $this->items(),
        ];
    }

    /**
     * Retrieve the items.
     *
     * @return array
     */
    public function items()
    {
        return get_field('items') ?: $this->example['items'];
    }

    /**
     * The block template.
     *
     * @var array
     */
    public $template = [
        'core/heading' => ['placeholder' => 'Hello World'],
        'core/paragraph' => ['placeholder' => 'Welcome to the Caroussel block.'],
    ];

    /**
     * Assets enqueued when rendering the block.
     */
    public function assets(array $block): void
    {
        //
    }
}
