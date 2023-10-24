<?php

namespace Apsonex\FilamentProducts;

use Livewire\Volt\Volt;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Asset;
use Illuminate\Filesystem\Filesystem;
use Spatie\LaravelPackageTools\Package;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\AlpineComponent;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Apsonex\FilamentProducts\Testing\TestsFilamentProducts;
use Apsonex\FilamentProducts\Commands\FilamentProductsCommand;
use Apsonex\FilamentProducts\Livewire\PricingTable;
use Livewire\Livewire;

class FilamentProductsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-products';

    public static string $viewNamespace = 'filament-products';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasRoutes(['web'])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('apsonex/filament-products');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }

        // Volt::mount([
        //     base_path('vendor/apsonex/filament-products/resources/views/volt')
        // ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(\Apsonex\FilamentProducts\FilamentProducts::class, FilamentProducts::class);
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-products/{$file->getFilename()}"),
                ], 'filament-products-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentProducts());

        Livewire::component('filament-products.pricing-table', PricingTable::class);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'apsonex/filament-products';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-products', __DIR__ . '/../resources/dist/components/filament-products.js'),
            // Css::make('filament-products-styles', __DIR__ . '/../resources/dist/filament-products.css'),
            // Js::make('filament-products-scripts', __DIR__ . '/../resources/dist/filament-products.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentProductsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [
            //
        ];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_products_table',
        ];
    }
}
