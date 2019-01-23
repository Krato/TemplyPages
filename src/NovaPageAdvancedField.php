<?php

namespace Infinety\TemplyPages;

use Laravel\Nova\Fields\Field;

class TemplyPagesField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'temply-pages-field';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;
}
