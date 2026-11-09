<?php

namespace App\Enums\InspectionObject;

enum Type: string
{
    case CRANE = 'crane';
    case OPERATOR_LIFT = 'operator_lift';
    case ATTACHMENT = 'attachment';

    public function label(): string
    {
        return __('enums/inspection_object/type.'.$this->value);
    }

    /**
     * Attachments are inspected on their own, but always as part of an
     * inspection of a main object (a crane or operator lift).
     */
    public function isAttachment(): bool
    {
        return $this === self::ATTACHMENT;
    }

    /**
     * The object types an attachment inspection can be linked to.
     *
     * @return list<self>
     */
    public static function mainObjectTypes(): array
    {
        return [self::CRANE, self::OPERATOR_LIFT];
    }

    public static function options(): array
    {
        return array_column(array_map(fn (self $type) => [$type->value, $type->label()], self::cases()), 1, 0);
    }
}
