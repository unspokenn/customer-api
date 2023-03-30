<?php

namespace App\Models;

use App\Models\EloquentStoredEvent;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEventQueryBuilder as BaseBuilder;

class EloquentStoredEventQueryBuilder extends BaseBuilder
{
    public function startingFrom(int $storedEventId): self
    {
        $this->where('id', '>=', $storedEventId);

        return $this;
    }

    public function afterVersion(int $version): self
    {
        $this->where('aggregate_version', '>', $version);

        return $this;
    }

    public function whereAggregateRoot(string $uuid): self
    {
        $this->where('aggregate_uuid', $uuid);

        return $this;
    }

    public function whereEvent(string ...$eventClasses): self
    {
        $this->whereIn('event_class', $eventClasses);

        return $this;
    }

    public function wherePropertyIs(string $property, mixed $value): self
    {
        $this->whereJsonContains(column: "event_properties->{$property}", value: $value);

        return $this;
    }

    public function wherePropertyIsNot(string $property, mixed $value): self
    {
        $this->whereJsonDoesntContain(column: "event_properties->{$property}", value: $value);

        return $this;
    }

    public function lastEvent(string ...$eventClasses): ?EloquentStoredEvent
    {
        return $this
            ->unless(
                empty($eventClasses),
                fn (self $query) => $query->whereEvent(...$eventClasses)
            )
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->first();
    }
}
