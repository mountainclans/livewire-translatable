<?php

namespace MountainClans\LivewireTranslatable\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

trait LogTranslatableAttributes
{
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnlyDirty()
            ->logOnly($this->fillable);
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        if (
            $eventName != 'deleted' ||
            method_exists($this, 'isForceDeleting') && !$this->isForceDeleting()
        ) {
            $translatableKeys = $this->getTranslatableAttributes();
            $dirtyProps = $this->getDirty();

            $old = [];
            $new = [];

            foreach ($dirtyProps as $dirtyKey => $dirtyValuesArray) {
                if (in_array($dirtyKey, ['created_at', 'updated_at'])) {
                    continue;
                }

                if (in_array($dirtyKey, $translatableKeys)) {
                    $oldValuesArray = $this->getOriginal($dirtyKey);
                    $newValuesArray = json_decode($dirtyValuesArray, 1);

                    foreach (array_keys($newValuesArray) as $lang) {
                        $oldValue = $oldValuesArray[$lang] ?? '';
                        $newValue = $newValuesArray[$lang];

                        if ($oldValue != $newValue) {
                            $old[$dirtyKey . '_' . $lang] = $oldValue;
                            $new[$dirtyKey . '_' . $lang] = $newValue;
                        }
                    }
                } else {
                    $old[$dirtyKey] = $this->getOriginal($dirtyKey);
                    $new[$dirtyKey] = $this->getAttribute($dirtyKey);
                }

                $activity->setAttribute('properties', array_replace_recursive(
                    $activity->properties->toArray(),
                    [
                        'old' => $old,
                        'attributes' => $new,
                    ]
                ));
            }
        }

        return $activity;
    }
}
