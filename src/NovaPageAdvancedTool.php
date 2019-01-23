<?php

namespace Infinety\TemplyPages;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool as BaseTool;

class TemplyPagesTool extends BaseTool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('temply-pages', __DIR__.'/../dist/js/tool.js');
        Nova::style('temply-pages', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('temply-pages::navigation');
    }
}
