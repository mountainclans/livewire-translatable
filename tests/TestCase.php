<?php

namespace MountainClans\LivewireTranslatable\Tests;

use Illuminate\Support\ViewErrorBag;
use Livewire\LivewireServiceProvider;
use MountainClans\LivewireTranslatable\LivewireTranslatableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Вне HTTP-запроса middleware ShareErrorsFromSession не отрабатывает,
        // а компонент обращается к $errors — расшариваем пустой bag вручную.
        $this->app['view']->share('errors', new ViewErrorBag);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            LivewireTranslatableServiceProvider::class,
        ];
    }
}
