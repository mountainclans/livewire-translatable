@use('MountainClans\LivewireTranslatable\Services\ContentLanguages')
@use('MountainClans\LivewireTranslatable\Services\TranslatableAttributes')

@php
    $languages = ContentLanguages::all();
    $tabsWithErrors = TranslatableAttributes::getTabsWithErrors($slot->toHtml(), $errors);
    $activeTab = !empty($tabsWithErrors) ? $tabsWithErrors[0] : array_keys($languages)[0];
@endphp

<div x-data="{
        activeTab: '{{ $activeTab }}'
    }"
     x-cloak
     wire:key="{{ 'translatable-tabs-' . uniqid() }}"
     class="py-2 px-4 border border-gray-600 border-dashed rounded-lg"
>
    <div
        class="text-xs font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px">

            @foreach($languages as $langCode => $langName)
                @php
                    $hasErrors = in_array($langCode, $tabsWithErrors) ? '1' : '0';
                @endphp
                <li class="me-2">
                    <button @click.prevent="activeTab = '{{ $langCode }}'"
                            class="inline-block p-3 rounded-t-lg border-b-2 text-sm cursor-pointer"
                            :class="{
                                'active': activeTab === '{{ $langCode }}',
                                'border-red-600': activeTab === '{{ $langCode }}' && {{ $hasErrors }} == 1,
                                'border-blue-600': activeTab === '{{ $langCode }}' && {{ $hasErrors }} == 0,
                                'border-red-300 hover:border-red-600': activeTab !== '{{ $langCode }}' && {{ $hasErrors }} == 1,
                                'border-blue-300 hover:border-blue-600': activeTab !== '{{ $langCode }}' && {{ $hasErrors }} == 0
                            }"
                            type="button"
                    >
                        {{ $langName }}
                    </button>
                </li>
            @endforeach

        </ul>
    </div>

    @foreach ($languages as $langCode => $langName)
        <div x-show="activeTab === '{{ $langCode }}'"
             class="mt-3"
        >
            @php
                echo TranslatableAttributes::makeWireablesTagsTranslatable($slot->toHtml(), $langCode, $errors);
            @endphp
        </div>
    @endforeach
</div>
