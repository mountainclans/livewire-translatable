<?php

use Illuminate\Support\Facades\Blade;
use MountainClans\LivewireTranslatable\LivewireTranslatableServiceProvider;
use MountainClans\LivewireTranslatable\Services\ContentLanguages;

it('boots the service provider', function () {
    expect(app()->getLoadedProviders())
        ->toHaveKey(LivewireTranslatableServiceProvider::class);
});

it('registers the x-ui.translatable blade component alias', function () {
    $aliases = app('blade.compiler')->getClassComponentAliases();

    expect($aliases)->toHaveKey('ui.translatable');
});

it('exposes the configured content languages', function () {
    expect(ContentLanguages::all())
        ->toHaveKey('en')
        ->toHaveKey('ru');
});

it('renders the x-ui.translatable component with a tab per language', function () {
    $html = Blade::render(
        '<x-ui.translatable><input wire:model="title.en" /></x-ui.translatable>'
    );

    expect($html)
        ->toContain('English')
        ->toContain('Русский');
});
