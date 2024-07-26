<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class About extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'About';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple About block.';

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
    public $icon = 'admin-users';

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
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'about_title' => 'About Us',
        'about_content' => 'We are a company dedicated to providing the best services to our clients.',
        'about_image' => [
            'url' => 'path/to/about-image.jpg',
            'alt' => 'About Image',
        ],
        'experience_years' => '25',
        'experience_text' => 'Years Experience',
    ];

    /**
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'about_title' => get_field('about_title'),
            'about_content' => get_field('about_content'),
            'about_image' => get_field('about_image'),
            'experience_years' => get_field('experience_years'),
            'experience_text' => get_field('experience_text'),
        ];
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $about = Builder::make('about');

        $about
            ->addText('about_title', [
                'label' => 'About Title',
                'required' => true,
            ])
            ->addTextarea('about_content', [
                'label' => 'About Content',
                'required' => true,
            ])
            ->addImage('about_image', [
                'label' => 'About Image',
                'required' => true,
            ])
            ->addText('experience_years', [
                'label' => 'Experience Years',
            ])
            ->addText('experience_text', [
                'label' => 'Experience Text',
            ]);

        return $about->build();
    }

    /**
     * Assets enqueued when rendering the block.
     */
    public function assets(array $block): void
    {
        //
    }
}
