<?php

namespace Kingmaker\Filament\FilterSets\Tests\Unit;

use Kingmaker\Filament\FilterSets\FilterSet;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestIcon;
use Kingmaker\Filament\FilterSets\Tests\TestCase;

class FilterSetTest extends TestCase
{
    public function test_it_exposes_the_name_it_was_made_with(): void
    {
        $this->assertSame('unread_quick_reads', FilterSet::make('unread_quick_reads')->getName());
    }

    public function test_the_label_falls_back_to_a_headline_of_the_name(): void
    {
        $this->assertSame('Unread Quick Reads', FilterSet::make('unread_quick_reads')->getLabel());
    }

    public function test_an_explicit_label_wins_over_the_fallback(): void
    {
        $this->assertSame(
            'Still To Read',
            FilterSet::make('unread_quick_reads')->label('Still To Read')->getLabel(),
        );
    }

    public function test_the_icon_and_colour_default_to_null(): void
    {
        $filterSet = FilterSet::make('example');

        $this->assertNull($filterSet->getIcon());
        $this->assertNull($filterSet->getColor());
    }

    public function test_it_accepts_a_string_icon(): void
    {
        $this->assertSame(
            'heroicon-o-rocket-launch',
            FilterSet::make('example')->icon('heroicon-o-rocket-launch')->getIcon(),
        );
    }

    public function test_it_accepts_a_backed_enum_icon(): void
    {
        $this->assertSame(
            TestIcon::RocketLaunch,
            FilterSet::make('example')->icon(TestIcon::RocketLaunch)->getIcon(),
        );
    }

    public function test_it_accepts_a_colour(): void
    {
        $this->assertSame('success', FilterSet::make('example')->color('success')->getColor());
    }

    public function test_filters_default_to_an_empty_array(): void
    {
        $this->assertSame([], FilterSet::make('example')->getFilters());
    }

    public function test_it_returns_the_filters_it_was_given(): void
    {
        $filters = [
            'is_published' => ['value' => false],
            'length' => ['length_range' => 'short'],
        ];

        $this->assertSame($filters, FilterSet::make('example')->filters($filters)->getFilters());
    }

    public function test_closure_filters_are_resolved_on_read(): void
    {
        $resolutions = 0;

        $filterSet = FilterSet::make('example')->filters(function () use (&$resolutions): array {
            $resolutions++;

            return ['is_published' => ['value' => true]];
        });

        $this->assertSame(0, $resolutions, 'The closure must not run until the filters are read.');

        $this->assertSame(['is_published' => ['value' => true]], $filterSet->getFilters());
        $this->assertSame(1, $resolutions);

        $filterSet->getFilters();
        $this->assertSame(2, $resolutions, 'Each read must re-resolve, so values can change at runtime.');
    }

    public function test_the_configuration_methods_are_chainable(): void
    {
        $filterSet = FilterSet::make('example');

        $this->assertSame($filterSet, $filterSet->label('Example'));
        $this->assertSame($filterSet, $filterSet->icon('heroicon-o-star'));
        $this->assertSame($filterSet, $filterSet->color('danger'));
        $this->assertSame($filterSet, $filterSet->filters([]));
    }
}
