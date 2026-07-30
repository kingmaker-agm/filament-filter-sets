<?php

namespace Kingmaker\Filament\FilterSets;

use BackedEnum;
use Closure;
use Illuminate\Support\Str;

class FilterSet
{
    protected ?string $label = null;

    protected string|BackedEnum|null $icon = null;

    protected ?string $color = null;

    /**
     * @var array<string, array<string, mixed>>|Closure
     */
    protected array|Closure $filters = [];

    final private function __construct(protected string $name) {}

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function label(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function icon(string|BackedEnum|null $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function color(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * The table filter form state to apply, keyed by filter name.
     *
     * @param  array<string, array<string, mixed>>|Closure  $filters
     */
    public function filters(array|Closure $filters): static
    {
        $this->filters = $filters;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label ?? Str::headline($this->name);
    }

    public function getIcon(): string|BackedEnum|null
    {
        return $this->icon;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getFilters(): array
    {
        return value($this->filters);
    }
}
