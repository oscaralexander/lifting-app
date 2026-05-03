<?php

function format_number(int|float $number, int $decimal_digits = 2): string
{
    $nf = new NumberFormatter(app()->getLocale(), NumberFormatter::DECIMAL);
    $nf->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, $decimal_digits);
    $nf->setAttribute(NumberFormatter::GROUPING_USED, 0);

    return $nf->format($number);
}
