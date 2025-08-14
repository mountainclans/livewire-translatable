<?php

namespace MountainClans\LivewireTranslatable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MountainClans\LivewireTranslatable\Services\ContentLanguages
 */
class ContentLanguages extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \MountainClans\LivewireTranslatable\Services\ContentLanguages::class;
    }
}
