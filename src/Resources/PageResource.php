<?php

namespace Infinety\TemplyPages\Resources;

use App\Models\Tenant\Page;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Infinety\TemplyPages\PageConfigurationResourceTool;
use Infinety\TemplyPages\TemplyPagesField;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class PageResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var  string
     */
    public static $model = Page::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var  string
     */
    public static $title = 'name';

    /**
     * @var string
     */
    public static $group = 'Content';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = true;

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
        return __('Pages');
    }

    /**
     * Get the displayble label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('Page');
    }

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey()
    {
        return 'pages';
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

            Text::make(__('Template'), function () {
                if ($this->template) {
                    return '<span class="text-primary">'.$this->template->name.'</span>';
                }
            })->asHtml()->exceptOnForms(),

            BelongsTo::make(__('Template'), 'template', TemplateResource::class)
                ->onlyOnForms()->hideWhenUpdating(),

            TemplyPagesField::make('data'),

            PageConfigurationResourceTool::make(),
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
