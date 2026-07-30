<?php

namespace Kingmaker\Filament\FilterSets\Tests\Fixtures;

/**
 * Stands in for Filament's own `Heroicon` enum, which differs between major
 * versions — `FilterSet::icon()` only promises to accept any `BackedEnum`.
 */
enum TestIcon: string
{
    case RocketLaunch = 'heroicon-o-rocket-launch';
}
