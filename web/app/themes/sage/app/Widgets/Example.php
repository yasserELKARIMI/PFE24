<?php

namespace App\Widgets;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Widget;

class Example extends Widget
{
    /**
     * The widget name.
     *
     * @var string
     */
    public $name = 'Example';

    /**
     * The widget description.
     *
     * @var string
     */
    public $description = 'This is a Example widget.';

    /**
     * Data to be passed to the widget before rendering.
     */
    public function with(): array
    {
        return [
            'items' => $this->items(),
        ];
    }

    /**
     * The widget title.
     */
    public function title(): string
    {
        return get_field('title', $this->widget->id);
    }

    /**
     * The widget field group.
     */
    public function fields(): array
    {
        $example = Builder::make('example');

        $example
            ->addText('title');

        $example
            ->addRepeater('items')
                ->addText('item')
            ->endRepeater();

        return $example->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function items()
    {
        return get_field('items', $this->widget->id) ?: [];
    }
}
