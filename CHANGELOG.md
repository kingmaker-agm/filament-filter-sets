# Changelog

All notable changes to `kingmaker/filament-filter-sets` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-07-30

First public release.

### Added

- `Table::filterSets()` macro for attaching predefined filter sets to any Filament table,
  plus the `Table::getFilterSets()` and `Table::getFilterSet()` reader macros.
- `FilterSet` value object with a fluent `make()` / `label()` / `icon()` / `color()` / `filters()` API.
  `filters()` accepts a `Closure` for state resolved at runtime.
- A dropdown trigger rendered into the table toolbar at `TablesRenderHook::TOOLBAR_SEARCH_AFTER`,
  scoped to the component that declares the sets.
- A Livewire `ComponentHook` supplying a virtual `applyTableFilterSet()` method, so consuming
  pages need no trait.
- Applying a set resets the filters form first, so sets replace the active filters rather than
  merging with them. State is filled through the schema so field state casts normalise it —
  a ternary `false` becomes the `0` of its rendered option.
- Support for both deferred and live table filters.
- Publishable views (`filament-filter-sets-views`) and translations (`filament-filter-sets-translations`).

[1.1.0]: https://github.com/kingmaker-agm/filament-filter-sets/releases/tag/v1.1.0
