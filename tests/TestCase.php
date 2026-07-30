<?php

namespace Kingmaker\Filament\FilterSets\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Kingmaker\Filament\FilterSets\FilterSetsServiceProvider;
use Kingmaker\Filament\FilterSets\Tests\Fixtures\TestPanelProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * Testbench does not run package discovery, so every provider a consuming
     * application would auto-discover has to be listed here — in the same order
     * Composer discovers them, alphabetically by package name.
     *
     * The order is not cosmetic: `filament/support` binds Livewire's `DataStore`
     * to its own subclass, and Livewire's provider re-registers it as a shared
     * instance afterwards. Booting Livewire first leaves `DataStore` as an
     * unshared binding, so every resolve returns a fresh store and component
     * state — the error bag among it — silently disappears between writes.
     *
     * @param  Application  $app
     * @return array<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            ActionsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            FilterSetsServiceProvider::class,
            LivewireServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            TestPanelProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        tap($app->make(Repository::class), function (Repository $config): void {
            $config->set('database.default', 'testing');
            $config->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
            $config->set('view.paths', array_merge(
                (array) $config->get('view.paths'),
                [__DIR__.'/Fixtures/views'],
            ));
        });
    }

    protected function defineDatabaseMigrations(): void
    {
        Schema::create('test_posts', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('word_count')->default(0);
            $table->timestamps();
        });
    }
}
