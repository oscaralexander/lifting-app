<?php

namespace App\Enums;

enum FieldType: string
{
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
    case NUMBER = 'number';
    case TOGGLE = 'toggle';
    case SELECT = 'select';
    case SELECT_MULTIPLE = 'select_multiple';

    public function icon(): string
    {
        return match ($this) {
            self::NUMBER => 'hash',
            self::SELECT => 'circle-check-big',
            self::SELECT_MULTIPLE => 'square-check-big',
            self::TEXT => 'type',
            self::TEXTAREA => 'text-align-start',
            self::TOGGLE => 'toggle-left',
        };
    }

    public function label(): string
    {
        return __('enums/field_type.' . $this->value);
    }
}
