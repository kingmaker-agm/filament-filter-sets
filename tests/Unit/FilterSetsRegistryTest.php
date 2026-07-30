<?php

namespace Kingmaker\Filament\FilterSets\Tests\Unit;

use Filament\Tables\Table;
use Kingmaker\Filament\FilterSets\FilterSet;
use Kingmaker\Filament\FilterSets\FilterSetsRegistry;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestTableComponent;
use Kingmaker\Filament\FilterSets\Tests\TestCase;

class FilterSetsRegistryTest extends TestCase
{
    private function makeTable(): Table
    {
        return Table::make(new TestTableComponent);
    }

    public function test_an_unregistered_table_has_no_filter_sets(): void
    {
        $this->assertSame([], app(FilterSetsRegistry::class)->get($this->makeTable()));
    }

    public function test_it_keys_filter_sets_by_name(): void
    {
        $registry = app(FilterSetsRegistry::class);
        $table = $this->makeTable();

        $drafts = FilterSet::make('drafts');
        $published = FilterSet::make('published');

        $registry->set($table, [$drafts, $published]);

        $this->assertSame(['drafts' => $drafts, 'published' => $published], $registry->get($table));
    }

    public function test_setting_again_replaces_the_previous_filter_sets(): void
    {
        $registry = app(FilterSetsRegistry::class);
        $table = $this->makeTable();

        $registry->set($table, [FilterSet::make('drafts')]);
        $registry->set($table, [FilterSet::make('published')]);

        $this->assertSame(['published'], array_keys($registry->get($table)));
    }

    public function test_filter_sets_are_scoped_to_the_table_instance(): void
    {
        $registry = app(FilterSetsRegistry::class);

        $first = $this->makeTable();
        $second = $this->makeTable();

        $registry->set($first, [FilterSet::make('drafts')]);

        $this->assertSame(['drafts'], array_keys($registry->get($first)));
        $this->assertSame([], $registry->get($second));
    }

    public function test_the_registry_is_a_singleton(): void
    {
        $this->assertSame(app(FilterSetsRegistry::class), app(FilterSetsRegistry::class));
    }
}
