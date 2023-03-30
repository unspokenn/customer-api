<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent as BaseModel;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEventCollection;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;
use Spatie\EventSourcing\StoredEvents\StoredEvent;
use Spatie\SchemalessAttributes\SchemalessAttributes;

class EloquentStoredEvent extends BaseModel
{
    public function toStoredEvent(): StoredEvent
    {
        return new StoredEvent([
            'id' => $this->id,
            'event_properties' => $this->event_properties,
            'aggregate_uuid' => $this->aggregate_uuid ?? '',
            'aggregate_version' => $this->aggregate_version ?? 0,
            'event_version' => $this->event_version,
            'event_class' => $this->event_class,
            'meta_data' => $this->meta_data,
            'created_at' => $this->created_at,
        ], $this->getOriginalEvent());
    }

    public function setOriginalEvent(ShouldBeStored $event): self
    {
        $this->originalEvent = $event;

        return $this;
    }

    public function getOriginalEvent(): ?ShouldBeStored
    {
        if ($this->isDirty('meta_data') || $this->wasChanged('meta_data')) {
            $this->originalEvent?->setMetaData($this->meta_data?->toArray() ?? []);
        }

        return $this->originalEvent;
    }

    public function getEventAttribute(): ?ShouldBeStored
    {
        return ($event = $this->getOriginalEvent())
            ? $event
            : $this->originalEvent = $this->toStoredEvent()->event;
    }

    public function getMetaDataAttribute(): SchemalessAttributes
    {
        return SchemalessAttributes::createForModel($this, 'meta_data');
    }

    public function newEloquentBuilder($query): EloquentStoredEventQueryBuilder
    {
        return new EloquentStoredEventQueryBuilder($query);
    }

    public function newCollection(array $models = []): EloquentStoredEventCollection
    {
        return new EloquentStoredEventCollection($models);
    }

    public function scopeWithMetaDataAttributes(): Builder
    {
        // Legacy support for laravel-schemaless-attributes:^1.0
        if (!method_exists($this->meta_data, 'modelScope')) {
            return $this->meta_data->scopeWithSchemalessAttributes('meta_data');
        }

        return $this->meta_data->modelScope();
    }
}
