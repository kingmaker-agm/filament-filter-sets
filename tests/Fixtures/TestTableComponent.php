<?php

namespace Kingmaker\Filament\FilterSets\Tests\Fixtures;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Kingmaker\Filament\FilterSets\FilterSet;
use Livewire\Component;

class TestTableComponent extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    /**
     * Both branches of `ApplyFilterSet::apply()` are reachable from a test by
     * flipping this before mounting.
     */
    public bool $deferFilters = true;

    public function table(Table $table): Table
    {
        return $table
            ->query(TestPost::query())
            ->deferFilters($this->deferFilters)
            ->columns([
                TextColumn::make('title'),
            ])
            ->filters([
                TernaryFilter::make('is_published'),
                Filter::make('length')
                    ->schema([
                        Select::make('length_range')
                            ->options([
                                'short' => 'Short',
                                'long' => 'Long',
                            ]),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => match ($data['length_range'] ?? null) {
                        'short' => $query->where('word_count', '<', 500),
                        'long' => $query->where('word_count', '>=', 500),
                        default => $query,
                    }),
            ])
            ->filterSets([
                FilterSet::make('draft_shorts')
                    ->label('Draft Shorts')
                    ->icon('heroicon-o-rocket-launch')
                    ->filters([
                        'is_published' => ['value' => false],
                        'length' => ['length_range' => 'short'],
                    ]),
                FilterSet::make('published_long_reads')
                    ->color('success')
                    ->filters(fn (): array => [
                        'is_published' => ['value' => true],
                        'length' => ['length_range' => 'long'],
                    ]),
            ]);
    }

    public function render(): View
    {
        return view('table-component');
    }
}
