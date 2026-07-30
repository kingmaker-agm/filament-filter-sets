<?php

namespace Kingmaker\Filament\FilterSets;

use Filament\Tables\Table;
use WeakMap;

class FilterSetsRegistry
{
    /**
     * Filter sets are keyed per Table instance in a WeakMap, since the
     * macroable Table class offers no property to store them on.
     *
     * @var WeakMap<Table, array<string, FilterSet>>
     */
    protected WeakMap $filterSets;

    public function __construct()
    {
        $this->filterSets = new WeakMap;
    }

    /**
     * @param  array<FilterSet>  $filterSets
     */
    public function set(Table $table, array $filterSets): void
    {
        $this->filterSets[$table] = collect($filterSets)
            ->mapWithKeys(fn (FilterSet $filterSet): array => [$filterSet->getName() => $filterSet])
            ->all();
    }

    /**
     * @return array<string, FilterSet>
     */
    public function get(Table $table): array
    {
        return $this->filterSets[$table] ?? [];
    }
}
