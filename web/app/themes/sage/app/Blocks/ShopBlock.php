<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use Log1x\AcfComposer\Builder;

class ShopBlock extends Block
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Shop Block';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Shop Block block.';

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
        $shopBlock = Builder::make('shop_block');

        $shopBlock
            ->addRepeater('items')
                ->addText('item')
            ->endRepeater();

        return $shopBlock->build();
    }

    public function items()
    {
        $orderby = request('orderby', 'menu_order'); // Default to 'menu_order' if not set
        $order = 'ASC'; // Default order
        $paged = max(1, get_query_var('paged', 1)); // Current page
        $posts_per_page = 12; // Number of products per page

        // Query to count total number of products
        $count_args = [
            'posts_per_page' => -1,
            'post_type' => 'product',
            'post_status' => 'publish',
        ];

        // Additional sorting parameters
        switch ($orderby) {
            case 'popularity':
                $count_args['meta_key'] = 'total_sales';
                $count_args['orderby'] = 'meta_value_num';
                break;
            case 'rating':
                $count_args['meta_key'] = '_wc_average_rating';
                $count_args['orderby'] = 'meta_value_num';
                break;
            case 'price':
                $count_args['meta_key'] = '_price';
                $count_args['orderby'] = 'meta_value_num';
                $count_args['order'] = 'ASC';
                break;
            case 'price-desc':
                $count_args['meta_key'] = '_price';
                $count_args['orderby'] = 'meta_value_num';
                $count_args['order'] = 'DESC';
                break;
            default:
                $count_args['orderby'] = 'menu_order';
                $count_args['order'] = 'ASC';
                break;
        }

        $total_query = new \WP_Query($count_args);
        $total_products = $total_query->found_posts;

        // Query for paginated results
        $args = [
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_type' => 'product',
            'orderby' => $orderby,
            'order' => $order,
        ];

        $query = new \WP_Query($args);

        // Map WooCommerce products to your expected format
        $formattedItems = [];
        foreach ($query->posts as $product_id) {
            $product = wc_get_product($product_id);
            $formattedItems[] = [
                'id' => $product->get_id(),
                'title' => $product->get_name(),
                'link' => get_permalink($product->get_id()),
                'image' => wp_get_attachment_url(get_post_thumbnail_id($product->get_id())),
                'price' => $product->get_price_html(),
                'regular_price' => $product->get_regular_price(),
                'cart_link' => $product->add_to_cart_url(),
            ];
        }

        return [
            'items' => $formattedItems,
            'max_num_pages' => $query->max_num_pages, // Maximum number of pages
            'current_page' => $paged, // Current page
            'total_products' => $total_products, // Total number of products
        ];
    }

    /**
     * Assets enqueued when rendering the block.
     */
    public function assets(array $block): void
    {
        //
    }
}
