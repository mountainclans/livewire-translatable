<?php

namespace MountainClans\LivewireTranslatable\Traits;

use MountainClans\LivewireTranslatable\Services\ContentLanguages;
use Throwable;

trait FilledTranslatableFields
{
    public function getAllTranslations(string $key)
    {
        try {
            $source = $this->getTranslations($key);
        } catch (Throwable $exception) {
            $source = [];
        }

        foreach (ContentLanguages::all() as $languageKey => $language) {
            if (!isset($source[$languageKey])) {
                $source[$languageKey] = '';
            }
        };

        return $source;
    }
}
