<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class CarouselStartBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Carousel Start Block';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Carousel Start Block block.';

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
     * The block keywords.
     *
     * @var array
     */
    public $keywords = [];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = [];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = [];

    /**
     * The ancestor block type allow list.
     *
     * @var array
     */
    public $ancestor = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = '';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

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
     * The block styles.
     *
     * @var array
     */
    public $styles = ['light', 'dark'];


    /**
     * The block template.
     *
     * @var array
     */
    public $template = [
        'core/heading' => ['placeholder' => 'Hello World'],
        'core/paragraph' => ['placeholder' => 'Welcome to the Carousel Start Block block.'],
    ];

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
     * The block field group.
     */
    public function fields(): array
    {
        $carouselStartBlock = Builder::make('carousel_start_block');

        $carouselStartBlock
        ->addRepeater('items', [
            'label' => 'Carousel',
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
                'rows' => 5,
                'required' => true,
            ])
            ->addText('title', [
                'label' => 'Title',
                'required' => true,
            ])
            ->addUrl('explore_more_link', [
                'label' => 'Explore More Link',
                'required' => true,
            ])
        ->endRepeater();

        return $carouselStartBlock->build();
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
     * Assets enqueued when rendering the block.
     */
    public function assets(array $block): void
    {
        //
    }
}
