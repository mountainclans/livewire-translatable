<?php

namespace MountainClans\LivewireTranslatable\Services;

class ContentLanguages
{
    public static array $languages = [];

    public static function all(): array
    {
        if (!empty($languages)) {
            return $languages;
        }

        $languages = config('livewire-translatable.content_languages');
        $languagesRows = explode(',', $languages);
        $result = [];

        foreach ($languagesRows as $languageRow) {
            $row = explode("=", $languageRow);
            $result[$row[0]] = $row[1];
        }

        self::$languages = $result;

        return $result;
    }

    public static function default(): string
    {
        $languages = self::all();

        return array_key_first($languages);
    }
}
