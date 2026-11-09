<?php

namespace App\Enums\InspectionObject\Attachment;

enum Type: string
{
    case FORK_CARRIAGE = 'fork_carriage';
    case MATERIAL_BUCKET = 'material_bucket';
    case AERIAL_WORK_PLATFORM_BUCKET = 'aerial_work_platform_bucket';
    case OTHER = 'other';

    public function label(): string
    {
        return __('enums/inspection_object/attachment/type.'.$this->value);
    }

    public static function options(): array
    {
        return array_column(array_map(fn (self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
