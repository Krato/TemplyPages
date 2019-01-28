<?php

namespace Infinety\TemplyPages;

use Laravel\Nova\ResourceTool;

class PageResourceTool extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return __('Fields');
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'fields-tool';
    }
}
