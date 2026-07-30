<?php

namespace Kingmaker\Filament\FilterSets;

use Filament\Tables\Contracts\HasTable;

class ApplyFilterSet
{
    /**
     * Applying a set replaces the currently active filters: the filters form
     * is reset to its defaults first, so the set's state merges over a fully
     * hydrated default structure.
     */
    public function apply(HasTable $livewire, FilterSet $filterSet): void
    {
        $livewire->resetTableFiltersForm();

        $filtersForm = $livewire->getTableFiltersForm();

        /**
         * Filling through the schema — rather than assigning the Livewire property — runs each
         * field's state casts, so a value such as a TernaryFilter's `false` is normalised to the
         * `0` of its `<option value="0">`. A native select carries no rendered `selected`
         * attribute and is bound to the raw state, so without this the field would show its
         * placeholder even though the filter was applied to the query.
         */
        $filtersForm->fill(array_replace_recursive(
            collect($filtersForm->getRawState())->toArray(),
            $filterSet->getFilters(),
        ));

        if ($livewire->getTable()->hasDeferredFilters()) {
            $livewire->applyTableFilters();

            return;
        }

        $livewire->updatedTableFilters();
    }
}
