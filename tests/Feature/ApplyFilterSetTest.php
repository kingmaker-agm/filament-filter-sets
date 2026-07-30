<?php

namespace Kingmaker\Filament\FilterSets\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestPost;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestTableComponent;
use Kingmaker\Filament\FilterSets\Tests\TestCase;
use Livewire\Livewire;

class ApplyFilterSetTest extends TestCase
{
    use RefreshDatabase;

    private function createPosts(): array
    {
        return [
            'draftShort' => TestPost::create(['title' => 'Draft Short', 'is_published' => false, 'word_count' => 100]),
            'draftLong' => TestPost::create(['title' => 'Draft Long', 'is_published' => false, 'word_count' => 1500]),
            'publishedShort' => TestPost::create(['title' => 'Published Short', 'is_published' => true, 'word_count' => 100]),
            'publishedLong' => TestPost::create(['title' => 'Published Long', 'is_published' => true, 'word_count' => 1500]),
        ];
    }

    public function test_applying_a_filter_set_narrows_the_records(): void
    {
        $posts = $this->createPosts();

        Livewire::test(TestTableComponent::class)
            ->call('applyTableFilterSet', 'draft_shorts')
            ->assertCanSeeTableRecords([$posts['draftShort']])
            ->assertCanNotSeeTableRecords([$posts['draftLong'], $posts['publishedShort'], $posts['publishedLong']]);
    }

    public function test_a_filter_set_built_from_a_closure_is_applied(): void
    {
        $posts = $this->createPosts();

        Livewire::test(TestTableComponent::class)
            ->call('applyTableFilterSet', 'published_long_reads')
            ->assertCanSeeTableRecords([$posts['publishedLong']])
            ->assertCanNotSeeTableRecords([$posts['draftShort'], $posts['draftLong'], $posts['publishedShort']]);
    }

    /**
     * The filters form binds its fields to `tableDeferredFilters`, and a native select matches
     * its options by the raw value bound there. A set's values must therefore be hydrated
     * through the schema so its state casts normalise them to the option values: a ternary
     * `false` has to become the `0` of its `<option value="0">`, otherwise the field renders
     * its placeholder instead. The assertions are strict because a raw `false` would loosely
     * equal `0` and hide the bug.
     */
    public function test_applying_a_filter_set_populates_the_filters_form_fields(): void
    {
        Livewire::test(TestTableComponent::class)
            ->call('applyTableFilterSet', 'draft_shorts')
            ->assertSetStrict('tableDeferredFilters.is_published.value', 0)
            ->assertSetStrict('tableDeferredFilters.length.length_range', 'short')
            ->call('applyTableFilterSet', 'published_long_reads')
            ->assertSetStrict('tableDeferredFilters.is_published.value', 1)
            ->assertSetStrict('tableDeferredFilters.length.length_range', 'long');
    }

    public function test_applying_a_filter_set_replaces_the_active_filters(): void
    {
        $posts = $this->createPosts();

        Livewire::test(TestTableComponent::class)
            ->call('applyTableFilterSet', 'draft_shorts')
            ->assertCanNotSeeTableRecords([$posts['publishedLong']])
            ->call('applyTableFilterSet', 'published_long_reads')
            ->assertCanSeeTableRecords([$posts['publishedLong']])
            ->assertCanNotSeeTableRecords([$posts['draftShort']]);
    }

    public function test_a_filter_set_clears_filters_it_does_not_declare(): void
    {
        Livewire::test(TestTableComponent::class)
            ->filterTable('length', ['length_range' => 'short'])
            ->assertSet('tableFilters.length.length_range', 'short')
            ->call('applyTableFilterSet', 'published_long_reads')
            ->assertSetStrict('tableFilters.length.length_range', 'long')
            ->call('applyTableFilterSet', 'draft_shorts')
            ->assertSetStrict('tableFilters.length.length_range', 'short');
    }

    public function test_applying_an_unknown_filter_set_leaves_the_filters_untouched(): void
    {
        $posts = $this->createPosts();

        Livewire::test(TestTableComponent::class)
            ->filterTable('is_published', true)
            ->call('applyTableFilterSet', 'nope')
            ->assertSet('tableFilters.is_published.value', true)
            ->assertCanSeeTableRecords([$posts['publishedShort'], $posts['publishedLong']])
            ->assertCanNotSeeTableRecords([$posts['draftShort'], $posts['draftLong']]);
    }

    public function test_it_applies_a_filter_set_on_a_table_with_live_filters(): void
    {
        $posts = $this->createPosts();

        Livewire::test(TestTableComponent::class, ['deferFilters' => false])
            ->call('applyTableFilterSet', 'draft_shorts')
            ->assertSetStrict('tableFilters.is_published.value', 0)
            ->assertSetStrict('tableFilters.length.length_range', 'short')
            ->assertCanSeeTableRecords([$posts['draftShort']])
            ->assertCanNotSeeTableRecords([$posts['draftLong'], $posts['publishedShort'], $posts['publishedLong']]);
    }
}
