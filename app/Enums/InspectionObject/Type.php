<?php

namespace App\Enums\InspectionObject;

enum Type: string
{
    case CRANE = 'crane';
    case OPERATOR_LIFT = 'operator_lift';
    case ATTACHMENT = 'attachment';
    case ARBO = 'arbo';

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
     * Only cranes and operator lifts have inspectable details of their own
     * (and with them a test matrix) and can have attachments linked to them.
     */
    public function isMainObject(): bool
    {
        return in_array($this, self::mainObjectTypes(), true);
    }

    /**
     * Attachments and other work equipment (Arbobesluit 7.4a) are never
     * inspected according to TCVT.
     */
    public function isAlwaysPeriodical(): bool
    {
        return in_array($this, [self::ATTACHMENT, self::ARBO], true);
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
