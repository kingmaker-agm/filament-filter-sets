<?php

namespace Kingmaker\Filament\FilterSets\Tests\Unit;

use Filament\Tables\Table;
use Kingmaker\Filament\FilterSets\FilterSet;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestTableComponent;
use Kingmaker\Filament\FilterSets\Tests\TestCase;

class TableMacroTest extends TestCase
{
    private function makeTable(): Table
    {
        return Table::make(new TestTableComponent);
    }

    public function test_the_macros_are_registered_on_the_table(): void
    {
        $this->assertTrue(Table::hasMacro('filterSets'));
        $this->assertTrue(Table::hasMacro('getFilterSets'));
        $this->assertTrue(Table::hasMacro('getFilterSet'));
    }

    public function test_filter_sets_returns_the_table_for_chaining(): void
    {
        $table = $this->makeTable();

        $this->assertSame($table, $table->filterSets([FilterSet::make('drafts')]));
    }

    public function test_get_filter_sets_returns_the_sets_keyed_by_name(): void
    {
        $drafts = FilterSet::make('drafts');

        $table = $this->makeTable()->filterSets([$drafts]);

        $this->assertSame(['drafts' => $drafts], $table->getFilterSets());
    }

    public function test_get_filter_sets_is_empty_when_none_are_declared(): void
    {
        $this->assertSame([], $this->makeTable()->getFilterSets());
    }

    public function test_get_filter_set_finds_a_set_by_name(): void
    {
        $drafts = FilterSet::make('drafts');

        $table = $this->makeTable()->filterSets([$drafts]);

        $this->assertSame($drafts, $table->getFilterSet('drafts'));
    }

    public function test_get_filter_set_returns_null_for_an_unknown_name(): void
    {
        $table = $this->makeTable()->filterSets([FilterSet::make('drafts')]);

        $this->assertNull($table->getFilterSet('nope'));
    }
}
