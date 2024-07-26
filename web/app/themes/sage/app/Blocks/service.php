<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Service extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Service';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Service block.';

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
    ];

    /**
     * The block styles.
     *
     * @var array
     */
    public $styles = ['light', 'dark'];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'items' => [
            [
                'image' => [
                    'url' => 'path/to/image-1.jpg',
                    'alt' => 'Service Image 1',
                ],
                'title' => 'Best Animal Selection',
                'text' => 'Our services include providing high-quality animals that are well-suited for dairy production, as well as offering advice and support on animal health and nutrition.',
                'button_text' => 'Read More',
                'button_link' => '#',
            ],
            [
                'image' => [
                    'url' => 'path/to/image-2.jpg',
                    'alt' => 'Service Image 2',
                ],
                'title' => 'Breeding & Veterinary',
                'text' => 'Our breeding services include artificial insemination, embryo transfer, and genetic selection. We also provide veterinary services such as health checks, vaccinations, and disease prevention.',
                'button_text' => 'Read More',
                'button_link' => '#',
            ],
            [
                'image' => [
                    'url' => 'path/to/image-3.jpg',
                    'alt' => 'Service Image 3',
                ],
                'title' => 'Care & Milking',
                'text' => 'Our team of experienced professionals is dedicated to providing the highest quality of care for your cows, from milking to nutrition and health management. We use the latest technology and techniques to ensure that your cows are healthy and producing the highest quality milk.',
                'button_text' => 'Read More',
                'button_link' => '#',
            ],
        ],
    ];

    /**
     * The block template.
     *
     * @var array
     */
    public $template = [
        'core/heading' => ['placeholder' => 'Hello World'],
        'core/paragraph' => ['placeholder' => 'Welcome to the Service block.'],
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
        $service = Builder::make('service');

        $service
            ->addRepeater('items')
                ->addImage('image', [
                    'label' => 'Image',
                    'required' => true,
                ])
                ->addText('title', [
                    'label' => 'Title',
                    'required' => true,
                ])
                ->addTextarea('text', [
                    'label' => 'Text',
                    'rows' => 4,
                ])
                ->addText('button_text', [
                    'label' => 'Button Text',
                ])
                ->addUrl('button_link', [
                    'label' => 'Button Link',
                ])
            ->endRepeater();

        return $service->build();
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
