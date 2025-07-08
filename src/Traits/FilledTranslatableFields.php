<?php

namespace MountainClans\LivewireTranslatable\Traits;

use MountainClans\LivewireTranslatable\Services\ContentLanguages;

trait FilledTranslatableFields
{
    public function getAllTranslations(string $key)
    {
        $source = $this->getTranslations($key);

        foreach (ContentLanguages::all() as $languageKey => $language) {
            if (!isset($source[$languageKey])) {
                $source[$languageKey] = '';
            }
        };

        return $source;
    }
}
