<?php

namespace Kingmaker\Filament\FilterSets;

use Closure;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\View\TablesRenderHook;
use Illuminate\Contracts\View\View;
use Livewire\ComponentHook;

class FilterSetsComponentHook extends ComponentHook
{
    protected bool $hasRegisteredRenderHook = false;

    /**
     * Provides a virtual `applyTableFilterSet()` method on every component
     * with a table, so consuming pages need no trait. Livewire fires this
     * hook before checking that the called method exists on the component.
     */
    public function call(string $method, array $params, Closure $returnEarly): void
    {
        if ($method !== 'applyTableFilterSet') {
            return;
        }

        if (! $this->component instanceof HasTable) {
            return;
        }

        $name = $params[0] ?? null;

        if (is_string($name)) {
            $filterSet = $this->component->getTable()->getFilterSet($name);

            if ($filterSet !== null) {
                app(ApplyFilterSet::class)->apply($this->component, $filterSet);
            }
        }

        $returnEarly();
    }

    public function render(): void
    {
        if ($this->hasRegisteredRenderHook) {
            return;
        }

        $component = $this->component;

        if (! $component instanceof HasTable) {
            return;
        }

        $filterSets = $component->getTable()->getFilterSets();

        if (empty($filterSets)) {
            return;
        }

        $this->hasRegisteredRenderHook = true;

        FilamentView::registerRenderHook(
            TablesRenderHook::TOOLBAR_SEARCH_AFTER,
            fn (): View => view('filament-filter-sets::trigger', [
                'filterSets' => $filterSets,
            ]),
            scopes: $component::class,
        );
    }
}
