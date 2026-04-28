<?php

namespace App\Enums;

enum InspectionType: string
{
    case TCVT = 'tcvt';
    case PERIODICAL = 'periodical';

    public function label(): string
    {
        return __('enums/inspection_type.'.$this->value);
    }
}
