<?php

namespace Infinety\TemplyPages\Resources;

use App\Models\Tenant\Template;
use App\Nova\Resource;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Infinety\TemplyPages\PageResourceTool;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class TemplateResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = Template::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'name';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var  array
     */
    public static $search = [
        'id', 'name',
    ];

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('Templates');
    }

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Template');
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'temply-pages-templates';
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function fields(Request $request)
    {
        return [
            ID::make('ID', 'id')
                ->sortable(),

            Text::make(__('Name'), 'name')
                ->rules('required')
                ->sortable(),

            PageResourceTool::make()->canSee(function ($request) {
                if (!app(Gate::class)->forUser(auth()->user())->has('manageTemplate')) {
                    return true;
                }

                return auth()->user()->can('manageTemplate');
            }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @return  array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
