<?php

namespace Infinety\TemplyPages;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Infinety\TemplyPages\Http\Middleware\Authorize;
use Infinety\TemplyPages\Models\Page;
use Infinety\TemplyPages\Observers\PageObserver;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class TemplyPagesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
            $this->collectionMacros();
        });

        $this->publishMigrations();

        // Nova::script('page-advanced-field', __DIR__.'/../dist/js/field.js');

        Nova::serving(function (ServingNova $event) {
            Nova::script('temply-pages-field', __DIR__.'/../dist/js/field.js');

            Page::observe(PageObserver::class);
        });

        Nova::resources([
            \Infinety\TemplyPages\Resources\TemplateResource::class,
            \Infinety\TemplyPages\Resources\PageResource::class,
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'temply-pages');
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/infinety/temply-pages')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Publish required migration
     */
    private function publishMigrations()
    {
        $this->publishes([
            __DIR__.'/Migrations/create_pages_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_pages_table.php'),
        ], 'temply-pages-migration');
    }

    /**
     * @return mixed
     */
    protected function collectionMacros()
    {
        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return collect($value)->recursive();
                }

                return $value;
            });
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
