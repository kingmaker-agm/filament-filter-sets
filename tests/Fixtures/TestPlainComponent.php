<?php

namespace Kingmaker\Filament\FilterSets\Tests\Fixtures;

use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * A component with no table at all — the component hook must leave it alone.
 */
class TestPlainComponent extends Component
{
    public function render(): View
    {
        return view('plain-component');
    }
}
