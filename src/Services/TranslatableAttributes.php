<?php

namespace MountainClans\LivewireTranslatable\Services;

use DOMDocument;
use DOMException;
use Illuminate\Support\ViewErrorBag;

class TranslatableAttributes
{
    public const INSERT_POSITION_NEXT_SIBLING = 'next_sibling';
    public const INSERT_POSITION_LAST_CHILD_IN_PARENT = 'last_child_in_parent';

    public static function getTabsWithErrors(string $html, ViewErrorBag $errors): array
    {
        $doc = self::initDomParser($html);

        $htmlTags = $doc->getElementsByTagName('*');
        $languages = ContentLanguages::all();
        $tabsWithErrors = [];

        foreach ($languages as $langCode => $language) {
            foreach ($htmlTags as $tag) {
                if ($tag->hasAttribute('translatable')) {
                    if ($tag->hasAttribute('wire:model')) {
                        $oldName = $tag->getAttribute('name');
                    } elseif ($tag->hasAttribute('data-model')) {
                        $oldName = $tag->getAttribute('data-model');
                    }

                    if (!empty($oldName)) {
                        $errorKey = $oldName . '.' . $langCode;
                        if ($errors->has($errorKey)) {
                            $tabsWithErrors[] = $langCode;
                            continue(2);
                        }
                    }
                }
            }
        }

        return $tabsWithErrors;
    }

    /**
     * @param string $html
     * @param string $lang
     * @param ViewErrorBag $errors
     * @return string
     * @throws DOMException
     */
    public static function makeWireablesTagsTranslatable(string $html, string $lang, ViewErrorBag $errors): string
    {
        $doc = self::initDomParser($html);

        $htmlTags = $doc->getElementsByTagName('*');

        foreach ($htmlTags as $tag) {
            if (!$tag->hasAttribute('translatable')) {
                continue;
            }

            /**
             * Атрибут wire:model присутствует у элементов, которые напрямую привязаны
             * к Livewire-компонентам, т.е. у input & textarea. Нам необходимо только подменить
             * ключ wire:model на локализованный ключ массива, куда будут записываться переводы поля.
             */
            if ($tag->hasAttribute('wire:model')) {
                $wireModel = $tag->getAttribute('wire:model') . '.' . $lang;
                $tag->setAttribute('wire:model', $wireModel);
            }

            /**
             * В случае с игнорированием механизмом Livewire поля при синхронизации значений данная привязка
             * выполняется в обход стандартной функциональности Livewire,то есть не через wire:model,
             * а с использованием $wire:entangle. Это полезно при работе с js-редакторами текста
             * (к примеру, TipTap). В данном случае мы задаём в blade-компоненте
             * data-x-template, содержащий в себе строку для подстановки
             * обновлённого значения, и затем заменяем изменённым шаблоном
             * x-data внутри blade-файла.
             */
            if ($tag->hasAttribute('data-model') && $tag->hasAttribute('data-x-template')) {
                $template = $tag->getAttribute('data-x-template');
                $tag->setAttribute('x-data', str_replace(
                    '::replace::',
                    $tag->getAttribute('data-model') . '.' . $lang,
                    $template
                ));
            }

            /**
             * Обновим id элемента и лейбл, т.к. он ссылается на id через for='...'
             */
            if ($tag->hasAttribute('id')) {
                $oldId = $tag->getAttribute('id');
                $newId = $oldId . '_' . $lang;
                $tag->setAttribute('id', $newId);

                $labels = $doc->getElementsByTagName('label');
                foreach ($labels as $label) {
                    if ($label->hasAttribute('for') && $label->getAttribute('for') === $oldId) {
                        $label->setAttribute('for', $newId);
                    }
                }
            }

            /**
             * Если тег - обычный input, у него будет задано имя и мы будем ориентироваться на него
             * для показа ошибок поля. Если атрибут - div для текстового редактора, то мы
             * ориентируемся на атрибут data-model
             *
             */
            if ($tag->hasAttribute('name')) {
                $oldName = $tag->getAttribute('name');
                $tag->setAttribute('name', $oldName . '_' . $lang); // обновим name
                $insertPosition = self::INSERT_POSITION_NEXT_SIBLING;
            } elseif ($tag->hasAttribute('data-model')) {
                $oldName = $tag->getAttribute('data-model');
                $insertPosition = self::INSERT_POSITION_LAST_CHILD_IN_PARENT;
            }

            /**
             * Ошибка валидации может присутствовать как в переводимом поле, так и поле, содержащем
             * все переводы. Обрабатываются оба этих варианта.
             */
            if (!empty($oldName) && !empty($insertPosition)) {
                $translatableErrorKey = $oldName . '.' . $lang;
                foreach ([$oldName, $translatableErrorKey] as $errorKey) {
                    if ($errors->has($errorKey)) {
                        $newElement = $doc->createElement('p', $errors->first($errorKey));
                        $newElement->setAttribute('class', 'mt-2 text-sm text-red-600 dark:text-red-500');
                        $newElement->setAttribute('error-bag', $errorKey);

                        if ($insertPosition == self::INSERT_POSITION_NEXT_SIBLING) {
                            $tag->parentNode->insertBefore($newElement, $tag->nextSibling);
                        } elseif ($insertPosition == self::INSERT_POSITION_LAST_CHILD_IN_PARENT) {
                            $tag->parentNode->parentNode->appendChild($newElement);
                        }
                    }
                }
            }
        }

        return $doc->saveHTML();
    }

    private static function initDomParser(string $html): DOMDocument
    {
        $doc = new DOMDocument();

        /**
         * Для подавления предупреждений при парсинге включаем режим внутренних ошибок.
         * Используем опции LIBXML_HTML_NOIMPLIED и LIBXML_HTML_NODEFDTD
         * для обработки фрагмента HTML без добавления <html>/<body>.
         * Затем восстанавливаем вывод ошибок.
         */
        libxml_use_internal_errors(true);

        // Важно: prepend UTF-8 declaration
        $doc->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        return $doc;
    }
}
