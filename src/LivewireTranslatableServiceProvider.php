<?php

namespace MountainClans\LivewireTranslatable;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LivewireTranslatableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('livewire-translatable')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        Blade::component('livewire-translatable::components/translatable', 'ui.translatable');
    }
}
