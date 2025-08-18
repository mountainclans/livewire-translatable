<?php

namespace MountainClans\LivewireTranslatable\Traits;

use MountainClans\LivewireTranslatable\Services\ContentLanguages;

trait RequireTranslations
{
    public function requireTranslations(
        string $fieldName,
        bool   $isOptional = false,
        bool   $requireDefaultLanguage = false,
        ?int    $minLength = null,
        ?int    $maxLength = null
    ): array
    {
        $languages = ContentLanguages::all();
        $rules = [];

        foreach ($languages as $locale => $language) {
            if (
                !$isOptional
                || (
                    $requireDefaultLanguage
                    && $locale === ContentLanguages::default()
                )
            ) {
                $rules["$fieldName.$locale"][] = [
                    'required',
                    'string',
                ];
            } else {
                $rules["$fieldName.$locale"][] = [
                    'nullable',
                ];
            }

            if (!is_null($minLength) && $minLength > 0) {
                $rules["$fieldName.$locale"][] = 'min:' . $minLength;
            }

            if (!is_null($maxLength) && $maxLength > 0) {
                $rules["$fieldName.$locale"][] = 'max:' . $maxLength;
            }
        }

        return $rules;
    }
}
