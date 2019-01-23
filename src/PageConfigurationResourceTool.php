<?php

namespace Infinety\TemplyPages;

use Laravel\Nova\ResourceTool;

class PageConfigurationResourceTool extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return __('Choose your design');
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'page-configuration';
    }
}
