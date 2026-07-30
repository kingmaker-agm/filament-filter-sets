<?php

namespace Kingmaker\Filament\FilterSets\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestPlainComponent;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestTableComponent;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestTableComponentWithoutFilterSets;
use Kingmaker\Filament\FilterSets\Tests\TestCase;
use Livewire\Livewire;

class TriggerRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_trigger_renders_an_item_for_every_filter_set(): void
    {
        Livewire::test(TestTableComponent::class)
            ->assertSeeHtml("applyTableFilterSet('draft_shorts')")
            ->assertSeeHtml("applyTableFilterSet('published_long_reads')");
    }

    public function test_the_trigger_shows_the_label_of_each_filter_set(): void
    {
        Livewire::test(TestTableComponent::class)
            ->assertSee('Draft Shorts')
            ->assertSee('Published Long Reads');
    }

    public function test_the_trigger_is_labelled_from_the_package_translations(): void
    {
        Livewire::test(TestTableComponent::class)
            ->assertSee('Filter sets');
    }

    public function test_a_table_without_filter_sets_renders_no_trigger(): void
    {
        Livewire::test(TestTableComponentWithoutFilterSets::class)
            ->assertDontSeeHtml('applyTableFilterSet');
    }

    public function test_applying_a_filter_set_on_a_table_without_any_is_a_no_op(): void
    {
        Livewire::test(TestTableComponentWithoutFilterSets::class)
            ->filterTable('is_published', true)
            ->call('applyTableFilterSet', 'draft_shorts')
            ->assertSet('tableFilters.is_published.value', true)
            ->assertDontSeeHtml('applyTableFilterSet');
    }

    public function test_the_hook_does_not_intercept_a_component_without_a_table(): void
    {
        $this->expectExceptionMessage('applyTableFilterSet');

        Livewire::test(TestPlainComponent::class)
            ->call('applyTableFilterSet', 'draft_shorts');
    }
}
