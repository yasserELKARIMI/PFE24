<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class Features extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Features';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Features block.';

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
                'title' => 'Why Us!',
                'heading' => 'Few Reasons Why People Choosing Us!',
                'paragraph' => 'People choose us because of our commitment to providing the best quality products, our dedication to customer service, and our competitive prices.Our products are made with the freshest ingredients and are free from preservatives and additives.',
                'features' => [
                    ['text' => 'We offer a wide variety of milk'],
                    ['text' => 'We offer a wide variety of cheese'],
                    ['text' => 'We offer a wide variety of yogurt'],
                ],
                'button_text' => 'Explore More',
                'button_link' => '#',
                'experience_img' => [
                    'url' => 'path/to/experience-image.jpg',
                    'alt' => 'Experience Image',
                ],
                'experience_years' => '25',
                'experience_text' => 'Years Experience',
                'award_img' => [
                    'url' => 'path/to/award-image.jpg',
                    'alt' => 'Award Image',
                ],
                'award_number' => '183',
                'award_text' => 'Award Winning',
                'animal_img' => [
                    'url' => 'path/to/animal-image.jpg',
                    'alt' => 'Animal Image',
                ],
                'animal_number' => '2619',
                'animal_text' => 'Total Animals',
                'client_img' => [
                    'url' => 'path/to/client-image.jpg',
                    'alt' => 'Client Image',
                ],
                'client_number' => '51940',
                'client_text' => 'Happy Clients',
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
        'core/paragraph' => ['placeholder' => 'Welcome to the Features block.'],
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
        $features = Builder::make('features');

        $features
            ->addRepeater('items')
                ->addText('title', [
                    'label' => 'Title',
                    'required' => true,
                ])
                ->addText('heading', [
                    'label' => 'Heading',
                    'required' => true,
                ])
                ->addTextarea('paragraph', [
                    'label' => 'Paragraph',
                    'rows' => 4,
                ])
                ->addRepeater('features', [
                    'label' => 'Features List',
                ])
                    ->addText('text', [
                        'label' => 'Feature Text',
                    ])
                ->endRepeater()
                ->addText('button_text', [
                    'label' => 'Button Text',
                ])
                ->addUrl('button_link', [
                    'label' => 'Button Link',
                ])
                ->addImage('experience_img', [
                    'label' => 'Experience Image',
                    'required' => true,
                ])
                ->addText('experience_years', [
                    'label' => 'Experience Years',
                ])
                ->addText('experience_text', [
                    'label' => 'Experience Text',
                ])
                ->addImage('award_img', [
                    'label' => 'Award Image',
                    'required' => true,
                ])
                ->addText('award_number', [
                    'label' => 'Award Number',
                ])
                ->addText('award_text', [
                    'label' => 'Award Text',
                ])
                ->addImage('animal_img', [
                    'label' => 'Animal Image',
                    'required' => true,
                ])
                ->addText('animal_number', [
                    'label' => 'Animal Number',
                ])
                ->addText('animal_text', [
                    'label' => 'Animal Text',
                ])
                ->addImage('client_img', [
                    'label' => 'Client Image',
                    'required' => true,
                ])
                ->addText('client_number', [
                    'label' => 'Client Number',
                ])
                ->addText('client_text', [
                    'label' => 'Client Text',
                ])
            ->endRepeater();

        return $features->build();
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
