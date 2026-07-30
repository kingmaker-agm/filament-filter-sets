<x-filament::dropdown placement="bottom-end">
    <x-slot name="trigger">
        <x-filament::icon-button
            icon="heroicon-s-swatch"
            color="gray"
            :label="__('filament-filter-sets::filter-sets.trigger.label')"
            :tooltip="__('filament-filter-sets::filter-sets.trigger.label')"
        />
    </x-slot>

    <x-filament::dropdown.list>
        @foreach ($filterSets as $filterSet)
            <x-filament::dropdown.list.item
                :icon="$filterSet->getIcon()"
                :color="$filterSet->getColor()"
                x-on:click="close()"
                wire:click="applyTableFilterSet('{{ $filterSet->getName() }}')"
            >
                {{ $filterSet->getLabel() }}
            </x-filament::dropdown.list.item>
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
