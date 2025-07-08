<?php

namespace MountainClans\LivewireTranslatable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MountainClans\LivewireTranslatable\Commands\LivewireTranslatableCommand;

class LivewireTranslatableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livewire-translatable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_livewire_translatable_table')
            ->hasCommand(LivewireTranslatableCommand::class);
    }
}
