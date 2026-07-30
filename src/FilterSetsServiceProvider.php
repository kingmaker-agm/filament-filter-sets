<?php

namespace Kingmaker\Filament\FilterSets;

use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class FilterSetsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(FilterSetsRegistry::class);

        $this->registerTableMacros();
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-filter-sets');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-filter-sets');

        Livewire::componentHook(FilterSetsComponentHook::class);

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/filament-filter-sets'),
        ], 'filament-filter-sets-views');

        $this->publishes([
            __DIR__.'/../resources/lang' => lang_path('vendor/filament-filter-sets'),
        ], 'filament-filter-sets-translations');
    }

    protected function registerTableMacros(): void
    {
        Table::macro('filterSets', function (array $filterSets): Table {
            /** @var Table $this */
            app(FilterSetsRegistry::class)->set($this, $filterSets);

            return $this;
        });

        Table::macro('getFilterSets', function (): array {
            /** @var Table $this */
            return app(FilterSetsRegistry::class)->get($this);
        });

        Table::macro('getFilterSet', function (string $name): ?FilterSet {
            /** @var Table $this */
            return app(FilterSetsRegistry::class)->get($this)[$name] ?? null;
        });
    }
}
