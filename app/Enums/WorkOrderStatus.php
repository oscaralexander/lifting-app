<?php

namespace App\Enums;

use Illuminate\Support\Collection;

enum WorkOrderStatus: string
{
    case COMPLETED = 'Afgehandeld';
    case COLLECTED = 'Opgehaald';
    case COMPLETE = 'Compleet';
    case READY = 'Klaargezet';

    public function label(): string
    {
        return $this->value;
    }

    /**
     * @return Collection<string, string>
     */
    public static function options(): Collection
    {
        return collect(self::cases())->mapWithKeys(fn (self $status) => [$status->value => $status->label()]);
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(fn (self $status) => $status->value, self::cases());
    }
}
