# Livewire Translatable

UI компонент для интуитивной работы с переводимыми полями. Опирается на функциональность, предоставляемую пакетом [Spatie Translatable](https://github.com/spatie/laravel-translatable).

## Установка

Установите пакет при помощи Composer:

```bash
composer require mountainclans/livewire-translatable
```


Опубликуйте файл конфигурации и задайте в нём нужные языки, на которые переводится ваш сайт:

```bash
php artisan vendor:publish --tag="livewire-translatable-config"
```

---

Опционально, вы можете опубликовать `views` для их переопределения:

```bash
php artisan vendor:publish --tag="livewire-translatable-views"
```

## Использование

Оберните translatable-поля компонентом `<x-ui.translatable>`:

```bladehtml
<x-ui.translatable>
    
    Переводимые поля, к примеру
    
    <x-ui.input wire:model="title" 
                translatable
                placeholder="{{ __('Enter the page title') }}"
                label="{{ __('Page title *') }}"
    />
    
</x-ui.translatable>
```

Каждому из переводимых полей необходимо добавить атрибут `translatable`. 

Используйте `<x-ui.input>` из [пакета UI]() и `<x-ui.tiptap>`.

### ContentLanguages

Пакет предоставляет сервис-класс `ContentLanguages`, имеющий два статических метода:

- `ContentLanguages::all()` - позволяет получить массив всех языков, на которые переводится приложение в формате `ключ` => `название языка`.
- `ContentLanguages::default()` - возвращает ключ первого языка, заданного в конфигурации.

## Изменения

Получите больше информации об изменениях в пакете, прочитав [CHANGELOG](CHANGELOG.md).

## Авторы

- [Vladimir Bajenov](https://github.com/mountainclans)
- [All Contributors](../../contributors)

## Лицензия

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
