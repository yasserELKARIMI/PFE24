<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class HeaderBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Header Block';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Header Block block.';

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
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

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
     * Data to be passed to the block before rendering.
     */
    public function with(): array
    {
        return [
            'title' => $this->getTitle(),
        ];
    }

    /**
     * Retrieve the page title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        // Get the current post object
        $post = get_post();

        // If the post object exists and is not empty
        if ($post && !empty($post->post_title)) {
            // Return the post title
            return $post->post_title;
        }

        // If no title is found, return an empty string
        return '';
    }

    /**
     * The block field group.
     */
    public function fields(): array
    {
        $headerBlock = Builder::make('header_block');

        $headerBlock
            ->addRepeater('Menu')
               
            ->endRepeater();

        return $headerBlock->build();
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
