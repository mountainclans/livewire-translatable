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

## Трейты

### Трейт FilledTranslatableFields 
Трейт позволяет задать пустые стартовые значения для всех языков, на которые переводится сайт, для только что инициализированного поля. Полезен при использовании с редактором, который требует строку при инициализации значения. 

**Использование:**

```php
# Your livewire component
//...
use MountainClans\LivewireTranslatable\Traits\FilledTranslatableFields;

public function mount(?string $blogId = null): void
    {
        if ($this->blogId) {
            $this->blog = Blog::findOrFail($this->blogId);

            $this->content = $this->blog->getAllTranslations('content');
            //...
        }
    }
```

Поле модели (в данном случае, `content`) обязательно должно быть указано в списке `$translatable` атрибутов модели.

### Трейт RequireTranslations

Предоставляет Livewire-компоненту метод `requireTranslations`, позволяющий убедиться при валидации поля, что у него есть переводы полей.

```php
# Your livewire component

public function saveSomething(): void
{
    $this->validate([
            'categoryId' => [
                'nullable',
                'exists:blog_categories,id',
            ],
            ...$this->requireTranslations('title', true, true, 10, 255),
    ]);
}
```

Метод принимает следующие параметры:

- string `$fieldName` - поле компонента, которое валидируем;
- bool `$isOptional` - можно ли оставить его пустым;
- bool `$requireDefaultLanguage` - требовать ли заполнения хотя бы ключа по умолчанию (получаемого из `ContentLanguages::default()`);
- int `$minLength` - минимальная длина поля;
- int `$maxLength` - максимальная длина поля.

### Трейт

Позволяет корректно логировать изменения в переводимых полях модели с использованием пакета [Spatie Activitylog](https://github.com/spatie/laravel-activitylog). 

```php 

class YourModel extends Model {
    use LogsActivity; // обязательно подключите трейт от Spatie
    use LogTranslatableAttributes;
    
    public array $translatable = [
        // ... 
    ];
}

```

Использование трейта опционально. Данный пакет не требует использования пакета Spatie Activitylog.

## Изменения

Получите больше информации об изменениях в пакете, прочитав [CHANGELOG](CHANGELOG.md).

## Авторы

- [Vladimir Bajenov](https://github.com/mountainclans)
- [All Contributors](../../contributors)

## Лицензия

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
